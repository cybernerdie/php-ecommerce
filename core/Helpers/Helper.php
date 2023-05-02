<?php

use App\Core\App;
use App\Exceptions\CustomException;

if (! function_exists('redirect')) {
    /**
     * Redirect to a new page.
     *
     * @param  string $path
     */
    function redirect($path)
    {
        header("Location: /{$path}");
    }
}

if (! function_exists('appenv')) {
    /**
     * Define a helper function to read from the environment
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function appenv($key, $default = null)
    {
        $path = dirname(__FILE__, 3) . '/.env';

        if (!file_exists($path)) {
            throw new CustomException('Missing .env file');
        }
        $config = parse_ini_file($path);
        return isset($config[$key]) ? $config[$key] : $default;
    }
}

if (! function_exists('user')) {
    /**
     * Get the current user in the request.
     *
     * @return mixed
     */
    function user()
    {
        $request = App::get('request');
        return $request->user();
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die.
     *
     * @param  mixed  $value
     * @return void
     */
    function dd($value)
    {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
        die;
    }
}

if (!function_exists('baseUrl')) {
    /**
     * Get the base url of the application
     *
     * @return string
     */
    function baseUrl(): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    }
}


