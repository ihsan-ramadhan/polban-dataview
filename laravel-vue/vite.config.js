import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        define: {
            API_BASE_URL: JSON.stringify(env.VITE_API_BASE_URL || '/api'),
        },
        plugins: [
            laravel({
                input: ['resources/css/main.css', 'resources/js/app.js'],
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
    };
});