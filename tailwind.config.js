/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                falaq: {
                    50: '#effaf3',
                    100: '#d9f3e2',
                    500: '#179d55',
                    600: '#128b49',
                    700: '#107340',
                    900: '#083d24',
                },
                ink: '#26332c',
                cream: '#fbfdf9',
            },
            fontFamily: {
                sans: ['"Noto Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Georgia', 'ui-serif', 'serif'],
            },
            boxShadow: {
                soft: '0 12px 32px rgba(16, 115, 64, 0.08)',
            },
        },
    },
    plugins: [],
};
