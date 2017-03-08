<?php

require '../vendor/autoload.php';
require '../helpers.php';

use Kriptonic\App\Core\Router;
use Kriptonic\App\Core\Request;

$router = new Router('../config/routes.php');
$router->dispatch(Request::method(), Request::uri());
