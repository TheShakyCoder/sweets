import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';

const port = 5173
const origin = `${process.env.DDEV_PRIMARY_URL}:${port}`

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        cors: true,
        host: "0.0.0.0",
        port: port,
        strictPort: true,
        origin: origin,
        hmr: {
            host: process.env.DDEV_HOSTNAME,
            protocol: 'wss'
        }
    },
});
