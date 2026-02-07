import forms from "@tailwindcss/forms";

export default {
    darkMode: ["class", "dark"],
    content: [
        "./resources/views/components/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
    ],
    plugins: [forms],
};
