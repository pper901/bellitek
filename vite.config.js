import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        https: true,
        host: '0.0.0.0',
    },
    build: {
        manifest: true,
        outDir: 'public/build',
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    base: process.env.VITE_APP_URL ? process.env.VITE_APP_URL + '/' : '/',
});
