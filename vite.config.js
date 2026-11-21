import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), ['VITE_', 'APP_URL']);
    const mobileOrigin = new URL(env.APP_URL || 'http://localhost:8000');

    if (env.VITE_HMR_HOST) {
        mobileOrigin.hostname = env.VITE_HMR_HOST;
    }

    return {
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
            host: env.VITE_HMR_HOST ? true : 'localhost',
            port: Number(env.VITE_PORT || 5173),
            strictPort: true,
            cors: {
                origin: [
                    /^https?:\/\/(?:(?:[^:]+\.)?localhost|127\.0\.0\.1|\[::1\])(?::\d+)?$/,
                    /^https?:\/\/.*\.test(:\d+)?$/,
                    ...(env.APP_URL ? [env.APP_URL] : []),
                    mobileOrigin.origin,
                ],
            },
            hmr: {
                host: env.VITE_HMR_HOST || 'localhost',
            },
            watch: {
                usePolling: env.VITE_USE_POLLING === 'true',
                interval: 300,
                ignored: ['**/storage/**', '**/vendor/**'],
            },
        },
    };
});
