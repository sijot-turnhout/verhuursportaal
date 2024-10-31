import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    /**
     * Suppressing Deprecations errors in the vite build because of the follwoing bootstrap tickets
     * Where we need to keep an eye on.
     *
     *! Temporary workaround
     *
     * @see https://github.com/twbs/bootstrap/issues/40849
     * @see https://github.com/twbs/bootstrap/issues/40962
     */
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: ['legacy-js-api', 'mixed-decls', 'color-functions', 'global-builtin', 'import']
            },
        }
    },
    plugins: [
        laravel({
            input: [
                'resources/css/filament/knowledge-base/theme.css',
                'resources/sass/app.scss',
                'resources/sass/invoice.scss',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: true,
        })
    ],
});
