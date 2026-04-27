<?php

namespace App\Providers;

use App\Filesystem\BackblazeVisibilityConverter;
use Aws\S3\S3Client;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Storage::extend('b2', function ($app, $config) {
            $client = new S3Client([
                'version'                 => 'latest',
                'region'                  => $config['region'],
                'endpoint'                => $config['endpoint'],
                'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
                'credentials'             => [
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ],
            ]);

            $adapter = new AwsS3V3Adapter(
                $client,
                $config['bucket'],
                $config['root'] ?? '',
                new BackblazeVisibilityConverter(),
            );

            // Ensure url is set so FilesystemAdapter::url() can construct public URLs.
            // Fall back to constructing it from endpoint + bucket if AWS_URL is absent.
            if (empty($config['url']) && !empty($config['endpoint'])) {
                $config['url'] = rtrim($config['endpoint'], '/') . '/' . $config['bucket'];
            }

            return new FilesystemAdapter(
                new Filesystem($adapter, ['visibility' => 'public']),
                $adapter,
                $config,
            );
        });

        // Belt-and-suspenders: if APP_URL is https, force the URL generator to
        // emit https:// links even if TrustProxies somehow misses the forwarded
        // scheme header. Prevents Mixed Content on Coolify's Traefik proxy.
        if (str_starts_with(config('app.url', ''), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
