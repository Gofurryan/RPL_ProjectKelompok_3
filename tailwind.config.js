/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class', // Agar fitur dark mode timmu aktif
    theme: {
        extend: {
            colors: {
                "primary": "#11d4d4",
                "background-light": "#f6f8f8",
                "background-dark": "#102222",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"]
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}