<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportWordPressPosts extends Command
{
    protected $signature = 'wp:import-posts
        {file=wp-export.json : Path to the JSON export file}
        {--disk=public : Storage disk for downloaded images (public, s3, etc.)}';

    protected $description = 'Import posts from a WordPress JSON export, converting HTML to Markdown and downloading images';

    /** @var array<string, Media> URL → Media record cache to avoid re-downloading duplicates */
    protected array $imageCache = [];

    protected string $disk;

    protected int $imagesDownloaded = 0;
    protected int $imagesFailed = 0;

    public function handle(): int
    {
        $path = base_path($this->argument('file'));
        $this->disk = $this->option('disk');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $json = json_decode(file_get_contents($path), true);
        $posts = $json['posts'] ?? [];

        $this->info("Found " . count($posts) . " posts to import.");
        $this->info("Images will be stored on the '{$this->disk}' disk.");
        $this->newLine();

        $imported = 0;
        $skipped = 0;

        foreach ($posts as $wp) {
            $slug = Str::slug($wp['slug'] ?: $wp['title']);

            if (Post::withTrashed()->where('slug', $slug)->exists()) {
                $this->line("  <comment>SKIP</comment>  {$slug} (already exists)");
                $skipped++;
                continue;
            }

            // Download images from the HTML before converting to Markdown.
            $html = $this->downloadAndReplaceImages($wp['content'] ?? '');
            $markdown = $this->htmlToMarkdown($html);

            Post::create([
                'title'       => html_entity_decode($wp['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'slug'        => $slug,
                'description' => Str::limit(strip_tags(html_entity_decode($wp['excerpt'] ?? '', ENT_QUOTES, 'UTF-8')), 255),
                'content'     => $markdown,
            ]);

            $this->line("  <info>OK</info>    {$slug}");
            $imported++;
        }

        $this->newLine();
        $this->info("Done. Imported: {$imported}, Skipped: {$skipped}");
        $this->info("Images downloaded: {$this->imagesDownloaded}, Failed: {$this->imagesFailed}");

        return self::SUCCESS;
    }

    /**
     * Find image URLs from woodvalecommunitycentre.co.uk, download them,
     * add them to the Media library, and replace the src with the new URL.
     */
    protected function downloadAndReplaceImages(string $html): string
    {
        // Match <img> tags with src pointing at the old WP domain
        return preg_replace_callback(
            '/<img([^>]+)src=["\']((https?:\/\/(?:www\.)?woodvalecommunitycentre\.org)[^"\']+)["\']([^>]*)>/si',
            function ($match) {
                $before  = $match[1];
                $url     = $match[2];
                $after   = $match[4];

                $media = $this->downloadImage($url);

                if ($media) {
                    $newUrl = $media->url;
                    return "<img{$before}src=\"{$newUrl}\"{$after}>";
                }

                // Download failed — leave original URL in place.
                return $match[0];
            },
            $html
        );
    }

    /**
     * Download a single image, store it on the configured disk,
     * and create a Media record. Returns null on failure.
     */
    protected function downloadImage(string $url): ?Media
    {
        // Return cached Media if we've already downloaded this URL.
        if (isset($this->imageCache[$url])) {
            return $this->imageCache[$url];
        }

        $this->line("    <comment>IMG</comment>  Downloading {$url}");

        $context = stream_context_create([
            'http' => [
                'timeout'       => 30,
                'user_agent'    => 'WACA-Importer/1.0',
                'follow_location' => true,
                'max_redirects' => 5,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        $data = @file_get_contents($url, false, $context);

        if ($data === false) {
            $this->line("    <error>FAIL</error> Could not download: {$url}");
            $this->imagesFailed++;
            return null;
        }

        // Determine filename and mime type
        $parsed   = parse_url($url);
        $basename = basename($parsed['path'] ?? 'image.jpg');
        $ext      = pathinfo($basename, PATHINFO_EXTENSION) ?: 'jpg';
        $mime     = $this->guessMime($ext);

        // Store on the configured disk
        $storagePath = 'media/' . Str::uuid() . '.' . $ext;

        try {
            Storage::disk($this->disk)->put($storagePath, $data);
        } catch (\Throwable $e) {
            $this->line("    <error>FAIL</error> Storage error: {$e->getMessage()}");
            $this->imagesFailed++;
            return null;
        }

        // Create the Media record
        $media = Media::create([
            'filename'    => $basename,
            'path'        => $storagePath,
            'disk'        => $this->disk,
            'mime_type'   => $mime,
            'size'        => strlen($data),
            'uploaded_by' => null,
        ]);

        $this->line("    <info>OK</info>   Stored as {$storagePath}");
        $this->imagesDownloaded++;

        $this->imageCache[$url] = $media;

        return $media;
    }

    protected function guessMime(string $ext): string
    {
        return match (strtolower($ext)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'gif'         => 'image/gif',
            'webp'        => 'image/webp',
            'svg'         => 'image/svg+xml',
            'avif'        => 'image/avif',
            default       => 'application/octet-stream',
        };
    }

    protected function htmlToMarkdown(string $html): string
    {
        // Normalise WordPress-isms
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        $html = preg_replace('/rn/', "\n", $html);
        $html = preg_replace('/\[caption[^\]]*\]/', '', $html);
        $html = str_replace('[/caption]', '', $html);

        // Strip WordPress shortcodes
        $html = preg_replace('/\[(\/?)[a-z_]+[^\]]*\]/', '', $html);

        // Remove inline Facebook emoji spans
        $html = preg_replace('/<span class="_5mfr"><span class="_6qdm">(.*?)<\/span><\/span>/', '$1', $html);

        // Headings
        $html = preg_replace_callback('/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/si', function ($m) {
            return "\n" . str_repeat('#', (int) $m[1]) . ' ' . strip_tags($m[2]) . "\n";
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
}
