import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Instrument Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': '#372c97',
                'primary-light': '#3b2db0',
                'yellow': '#f7de0c',
                'yellow-light': '#fefbe7',
            },
        },
    },
    plugins: [],
};
