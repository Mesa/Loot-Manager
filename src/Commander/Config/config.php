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
    ],
    'bundles'  => [
        "LootManager" => ROOT . "src",
        "JackAssPHP"  => ROOT . "src",
    ],
    'twig' => [
        'options' => [
            'cache' => '%path.cache%/twig'
        ]
    ],
    'path'     => [
        'cache'      => ROOT . 'cache',
        'controller' => ['\Commander\Controller' => new SplFileInfo(__DIR__ . '/../Controller')],
        'templates' => []
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
