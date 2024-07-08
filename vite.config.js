import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import purge from "@erbelion/vite-plugin-laravel-purgecss";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/filament/knowledge-base/theme.css',
                'resources/sass/app.scss',
                'resources/sass/invoice.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        })
    ],
});
