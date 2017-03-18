<?php

/**
 * Application entry-point.
 *
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */

require '../bootstrap.php';
require '../helpers.php';

use Kriptonic\App\Core\Router;
use Kriptonic\App\Core\Request;

session_start();

$router = new Router('../config/routes.php');
$router->dispatch(Request::method(), Request::uri());
