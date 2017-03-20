<?php

namespace Kriptonic\App\Core;

/**
 * Class Request
 *
 * The Request object contains helper methods used to get information about the current request.
 *
 * @package Kriptonic\App\Core
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */
class Request
{
    /**
     * Gets the request uri.
     *
     * @return string The request uri.
     */
    public static function uri()
    {
        return trim($_SERVER['REQUEST_URI'], '/');
    }

    /**
     * Gets the request method.
     *
     * @return string The request method.
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get posted data.
     *
     * @param null|string $parameter The key to get from the $_POST data.
     * @param null|mixed $default The value to return if $_POST[$parameter] does not exist.
     * @return mixed Either $_POST[$parameter] or $default values.
     */
    public static function input($parameter = null, $default = null)
    {
        if ($parameter === null) {
            return $_POST;
        }

        if (isset($_POST[$parameter])) {

            // Return null for empty values.
            if (is_string($_POST[$parameter]) && !strlen($_POST[$parameter])) {
                return null;
            }

            return $_POST[$parameter];
        }

        return $default;
    }

    /**
     * Get query parameter data.
     *
     * @param null|string $parameter The key to get from the $_GET data.
     * @param null|mixed $default The value to return if $_GET[$parameter] does not exist.
     * @return mixed Either $_GET[$parameter] or $default value.
     */
    public static function query($parameter = null, $default = null)
    {
        if ($parameter === null) {
            return $_GET;
        }

        if (isset($_GET[$parameter])) {

            // Return null for empty values.
            if (is_string($_GET[$parameter]) && !strlen($_GET[$parameter])) {
                return null;
            }

            return $_GET[$parameter];
        }

        return $default;
    }
}
