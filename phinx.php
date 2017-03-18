<?php

/**
 * Phinx configuration file for database migrations.
 *
 * @author Christopher Sharman <christopher.p.sharman@gmail.com>
 */

$appConfig = require 'config/config.php';

$dbConfig = $appConfig['database'];

$dbName = $dbConfig['name'];

// Configure PDO so that phinx can do its migrations.
$dsn = sprintf('mysql:host=%s;dbname=%s', $dbConfig['host'], $dbName);
$pdo = new \PDO($dsn, $dbConfig['user'], $dbConfig['pass']);

// Return settings for phinx configuration.
return [
    'paths' => [
        'migrations' => 'config/migrations',
        'seeds' => 'config/seeds'
    ],
    'environments' => [
        'default_database' => $dbName,
        $dbName => [
            'name' => $dbName,
            'connection' => $pdo
        ]
    ]
];
