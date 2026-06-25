export default {
    darkMode: 'class',

    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './node_modules/flowbite/**/*.js',
    ],

    plugins: [
        require('flowbite/plugin'),
    ],
}
