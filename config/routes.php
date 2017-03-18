<?php

/**
 * The routes file for the application.
 */

$router->get('', 'PuzzleController@play');
$router->post('', 'PuzzleController@validate');

$router->get('register', 'AccountController@register');
$router->post('register', 'AccountController@store');

$router->get('login', 'AccountController@login');
$router->post('login', 'AccountController@doLogin');
$router->get('logout', 'AccountController@logout');
