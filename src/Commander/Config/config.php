<?php

return [
    'config'   => [
        'controller' => [
            'filePattern'   => '*Controller.php',
            'methodPattern' => '/.*$/'
        ]
    ],
    'doctrine' => [
        'params'   => [
            'driver' => 'pdo_sqlite',
            'path'   => ROOT . 'db.sqlite',
            'dbname' => ''
        ],
        'entities' => [
            ROOT . 'App/Entity'
        ]
    ],
    'bundles'  => [
        "LootManager" => ROOT . "src",
        "JackAssPHP"  => ROOT . "src",
    ],
    'path'     => [
        'cache'      => ROOT . 'cache',
        'controller' => ['\Commander\Controller' => new SplFileInfo(__DIR__ . '/../../Controller')]
    ],
    'system'   => [
        'debug'           => false,
        'error_reporting' => 0,
        'path'            => [
            'bundles' => 'src',
            'app'     => 'App',
            'web'     => 'public'
        ],
        'bundles'         => [
            'Commander' => ROOT . 'src',
            'Mesa'      => ROOT
        ]
    ],
];
