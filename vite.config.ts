import { defineConfig } from 'vite';
import { resolve } from 'path';
import cssInjectedByJsPlugin from 'vite-plugin-css-injected-by-js';

export default defineConfig({
    plugins: [
        // Inject CSS into JS bundle so FilePond styles are included
        cssInjectedByJsPlugin(),
    ],
    define: {
        // Replace process.env.NODE_ENV for browser compatibility
        'process.env.NODE_ENV': JSON.stringify('production'),
    },
    build: {
        lib: {
            entry: resolve(__dirname, 'resources/js/index.ts'),
            name: 'AcceladeForms',
            fileName: (format) => `forms.${format}.js`,
            formats: ['es', 'iife'],
        },
        outDir: 'dist',
        sourcemap: true,
        minify: 'terser',
        rollupOptions: {
            output: {
                globals: {},
            },
        },
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
