import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'; //добавлено

export default defineConfig({
    plugins: [
        laravel({
            input: [
                //'resources/sass/app.scss', (убрано)
                'resources/css/app.css', //добавлено
                'resources/js/jquery.js', //добавлено
                'resources/js/app.js',
                'resources/js/bootstrap.js' //добавлено
            ],
            refresh: true,
        }),
    ],
//добавлено
    resolve: {
        alias: {
            //'$': 'jQuery',
            '~jquery': path.resolve(__dirname, 'node_modules/jquery'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap')
        }
    },
    //добавлено
});
