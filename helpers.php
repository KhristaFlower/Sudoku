<?php

use Kriptonic\App\Core;

/**
 * This file contains utility methods that wrap around commonly used features.
 *
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */

/**
 * A utility method for returning a view response from a controller action.
 *
 * @param string $file The name of the view file to load.
 * @param array $data The array data to be displayed in the view.
 * @return Core\View|Core\Response The view response object.
 */
function view($file, $data = [])
{
	return new Core\View($file, $data);
}

/**
 * A utility method for returning a redirect response from a controller action.
 *
 * @param string $location The location to redirect to.
 * @return Core\Redirect|Core\Response The redirect response object.
 */
function redirect($location)
{
	return new Core\Redirect($location);
}
