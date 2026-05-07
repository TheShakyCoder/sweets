<?php

/**
 * Import posts from wp-export.json into the Laravel database.
 *
 * Converts WordPress HTML content to Markdown and creates Post records.
 * Safe to run multiple times — skips posts whose slug already exists.
 *
 * Usage:
 *   php artisan tinker < import-posts.php
 *   — or —
 *   ddev artisan tinker < import-posts.php
 */

use App\Models\Post;
use Illuminate\Support\Str;

$json = json_decode(file_get_contents(base_path('wp-export.json')), true);
$posts = $json['posts'] ?? [];

echo "Found " . count($posts) . " posts to import.\n\n";

$imported = 0;
$skipped = 0;

foreach ($posts as $wp) {
    $slug = Str::slug($wp['slug'] ?: $wp['title']);

    if (Post::withTrashed()->where('slug', $slug)->exists()) {
        echo "  SKIP  {$slug} (already exists)\n";
        $skipped++;
        continue;
    }

    $markdown = htmlToMarkdown($wp['content'] ?? '');

    Post::create([
        'title'       => html_entity_decode($wp['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
        'slug'        => $slug,
        'description' => Str::limit(strip_tags(html_entity_decode($wp['excerpt'] ?? '', ENT_QUOTES, 'UTF-8')), 255),
        'content'     => $markdown,
    ]);

    echo "  OK    {$slug}\n";
    $imported++;
}

echo "\nDone. Imported: {$imported}, Skipped: {$skipped}\n";

// ─── HTML → Markdown converter ──────────────────────────────────────

function htmlToMarkdown(string $html): string
{
    // Normalise WordPress-isms
    $html = str_replace(["\r\n", "\r"], "\n", $html);
    $html = preg_replace('/rn/', "\n", $html);           // Literal "rn" from the dump
    $html = preg_replace('/\[caption[^\]]*\]/', '', $html);
    $html = str_replace('[/caption]', '', $html);

    // Strip WordPress shortcodes (e.g. [video ...][/video], [gallery ...])
    $html = preg_replace('/\[(\/?)[a-z_]+[^\]]*\]/', '', $html);

    // Remove inline Facebook emoji spans
    $html = preg_replace('/<span class="_5mfr"><span class="_6qdm">(.*?)<\/span><\/span>/', '$1', $html);

    // Headings
    $html = preg_replace_callback('/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/si', function ($m) {
        return "\n" . str_repeat('#', (int)$m[1]) . ' ' . strip_tags($m[2]) . "\n";
    }, $html);

    // Bold
    $html = preg_replace('/<(strong|b)>(.*?)<\/\1>/si', '**$2**', $html);

    // Italic
    $html = preg_replace('/<(em|i)>(.*?)<\/\1>/si', '*$2*', $html);

    // Links
    $html = preg_replace_callback('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>(.*?)<\/a>/si', function ($m) {
        $text = strip_tags($m[2]);
        return "[{$text}]({$m[1]})";
    }, $html);

    // Images
    $html = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/si', function ($m) {
        $alt = '';
        if (preg_match('/alt=["\']([^"\']*)["\']/', $m[0], $a)) {
            $alt = $a[1];
        }
        return "![{$alt}]({$m[1]})";
    }, $html);

    // Unordered lists
    $html = preg_replace('/<ul[^>]*>/i', "\n", $html);
    $html = preg_replace('/<\/ul>/i', "\n", $html);
    $html = preg_replace('/<li[^>]*>(.*?)<\/li>/si', "- $1\n", $html);

    // Ordered lists
    $html = preg_replace('/<ol[^>]*>/i', "\n", $html);
    $html = preg_replace('/<\/ol>/i', "\n", $html);

    // Blockquote
    $html = preg_replace('/<blockquote[^>]*>(.*?)<\/blockquote>/si', "> $1\n", $html);

    // Paragraphs and line breaks
    $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
    $html = preg_replace('/<p[^>]*>/i', "\n", $html);
    $html = str_replace('</p>', "\n", $html);

    // Divs
    $html = preg_replace('/<\/?div[^>]*>/i', "\n", $html);

    // Strip remaining tags
    $html = strip_tags($html);

    // Decode HTML entities
    $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Clean up whitespace
    $html = preg_replace('/\n{3,}/', "\n\n", $html);
    $html = preg_replace('/[ \t]+\n/', "\n", $html);

    return trim($html);
}
