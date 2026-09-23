import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                manrope: ['Manrope', ...defaultTheme.fontFamily.sans],
                cabin: ['Cabin', ...defaultTheme.fontFamily.sans],
                instrument: ['Instrument Serif', 'Georgia', 'serif'],
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0D9488',
                secondary: '#14B8A6',
                accent: '#EA580C',
            },
        },
    },
    plugins: [forms],
};
