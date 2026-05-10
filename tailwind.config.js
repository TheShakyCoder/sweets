import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#fff0f6',
                    100: '#ffe0ed',
                    200: '#ffc2db',
                    300: '#ff8abb',
                    400: '#ff4d94',
                    500: '#ff1a75',
                    600: '#e60066',
                    700: '#c20055',
                    800: '#9e0046',
                    900: '#78003a',
                },
                accent: {
                    50:  '#fffbeb',
                    100: '#fff3c4',
                    200: '#ffe588',
                    300: '#ffd43b',
                    400: '#ffc107',
                    500: '#f5a623',
                    600: '#e09100',
                    700: '#b87300',
                },
                candy: {
                    purple: '#8b5cf6',
                    magenta: '#d946ef',
                    blue:   '#06b6d4',
                    green:  '#10b981',
                    orange: '#f97316',
                    red:    '#ef4444',
                    yellow: '#fbbf24',
                    pink:   '#f472b6',
                },
                warm: {
                    50:  '#fafaf9',
                    100: '#f5f5f4',
                    200: '#e7e5e4',
                    300: '#d6d3d1',
                    400: '#a8a29e',
                    500: '#78716c',
                    600: '#57534e',
                    700: '#44403c',
                    800: '#292524',
                    900: '#1c1917',
                },
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) rotate(var(--tw-rotate, 0deg))' },
                    '50%': { transform: 'translateY(-20px) rotate(var(--tw-rotate, 0deg))' },
                },
                'float-slow': {
                    '0%, 100%': { transform: 'translateY(0) rotate(var(--tw-rotate, 0deg))' },
                    '50%': { transform: 'translateY(-30px) rotate(var(--tw-rotate, 0deg))' },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-6deg)' },
                    '50%': { transform: 'rotate(6deg)' },
                },
                'scale-pulse': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.08)' },
                },
            },
            animation: {
                float: 'float 4s ease-in-out infinite',
                'float-slow': 'float-slow 6s ease-in-out infinite',
                'float-delayed': 'float 5s ease-in-out 1s infinite',
                wiggle: 'wiggle 2s ease-in-out infinite',
                'scale-pulse': 'scale-pulse 3s ease-in-out infinite',
            },
        },
    },

    plugins: [forms, typography],
};
