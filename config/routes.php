<?php

/**
 * The routes file for the application.
 */

$router->get('', 'PuzzleController@play');
$router->post('', 'PuzzleController@validate');
