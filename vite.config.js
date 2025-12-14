import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: process.env.PORT ? '0.0.0.0' : 'localhost',
        port: process.env.PORT ? parseInt(process.env.PORT) : 5173,
        strictPort: true,
    },
});

