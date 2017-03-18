<?php

/**
 * Setup the application.
 *
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */

require 'vendor/autoload.php';

$config = require 'config/config.php';

/**
 * Handle the setup of Eloquent so that we can make use of its model system.
 */

$capsule = new Illuminate\Database\Capsule\Manager();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $config['database']['host'],
    'database' => $config['database']['name'],
    'username' => $config['database']['user'],
    'password' => $config['database']['pass'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

