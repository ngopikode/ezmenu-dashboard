import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/invite.css',
                'resources/js/invite.js',
                'resources/views/themes/elegant-theme/wedding/gold-wedding/assets/css/app.css',
                'resources/views/themes/elegant-theme/wedding/gold-wedding/assets/js/app.js'
            ],
            refresh: true
        })
    ]
});
