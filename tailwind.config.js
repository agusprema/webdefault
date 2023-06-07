const colors = require("tailwindcss/colors");

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/awcodes/curator/resources/views/**/*.blade.php",
        "./vendor/wire-elements/modal/resources/views/*.blade.php",
    ],
    darkMode: "class",
    safelist: [
        "sm:max-w-md",
        "md:max-w-xl",
        "lg:max-w-3xl",
        "xl:max-w-5xl",
        "2xl:max-w-7xl",
    ],
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
