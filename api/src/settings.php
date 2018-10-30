<?php



return [
    'settings' => [
        'displayErrorDetails'    => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer'               => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        //environment
        'environment' => getenv('ENV'),

        //disable cache
        'disableCache' => false,

        // Monolog settings
        'logger'                 => [
            'name'  => 'slim-app',
            'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db'                     => [
            'driver'    => getenv('DB_DRIVER'),
            'database'  => getenv('DB_DATABASE'),
            'charset'   => getenv('DB_CHARSET'),
            'collation' => getenv('DB_COLLATION'),
            'prefix'    => getenv('DB_COLLATION'),
        ],
    ],
];
