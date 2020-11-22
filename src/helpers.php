<?php

if (!function_exists('themes_path')) {
    /**
     * Get the path to the themes folder.
     *
     * @param string $path
     * @return string
     */
    function themes_path($path = '')
    {
        return app()->basePath() . DIRECTORY_SEPARATOR . 'themes' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('theme_assets')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @return string
     */
    function theme_assets($path, $secure = null)
    {
        $theme = app('theman')->getTheme();

        if ($theme && file_exists(base_path("themes/$theme/assets/$path"))) {
            return app('url')->asset("themes/$theme/assets/$path", $secure);
        }

        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('secure_theme_assets')) {
    /**
     * Generate a secure asset path for the theme asset.
     *
     * @param string $path
     * @return string
     */
    function secure_theme_assets($path)
    {
        return theme_assets($path, true);
    }
}
