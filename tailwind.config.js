const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        './app/filament/**/*.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/filament/**/*.blade.php',
        "./vendor/awcodes/filament-table-repeater/resources/views/**/*.blade.php",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                danger: colors.red,
                primary: colors.indigo,
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
