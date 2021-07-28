let tailwindcss = require('tailwindcss');

module.exports = {
    plugins: [
        tailwindcss('./assets/tailwind.config.js'), // your tailwind.js configuration file path
        require('autoprefixer'),
        require('postcss-import')
    ]
}