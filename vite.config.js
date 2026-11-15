import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            detectTls: false,
            fonts: [
                bunny('Plus Jakarta Sans', {
                    weights: [400, 500, 600, 700, 800],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: process.env.VITE_HMR_HOST ? true : 'localhost',
        port: Number(process.env.VITE_PORT || 5173),
        strictPort: true,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
        },
        watch: {
            usePolling: process.env.VITE_USE_POLLING === 'true',
            interval: 300,
            ignored: ['**/storage/**', '**/vendor/**'],
        },
    },
});
