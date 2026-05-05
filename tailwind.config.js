import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50:  '#EEF2FB',
                    100: '#D6DEF2',
                    200: '#A7B6E1',
                    300: '#7B8ECF',
                    400: '#4F65BC',
                    500: '#2C44A3',
                    600: '#1B3187',
                    700: '#13256B',
                    800: '#0B1E4F',  // primary deep blue
                    900: '#06122F',
                },
                gold: {
                    100: '#FBF1D9',
                    200: '#F2DFA6',
                    300: '#E6C977',
                    400: '#D4B259',
                    500: '#C9A24C',  // primary gold
                    600: '#A88231',
                    700: '#856420',
                },
            },
            boxShadow: {
                'soft': '0 4px 24px -8px rgba(11, 30, 79, 0.12)',
                'gold': '0 4px 14px -2px rgba(201, 162, 76, 0.35)',
            },
        },
    },
    plugins: [forms, typography],
};
