<?php
return [
    /* Database access */
    'database' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'anexseller',
        'username'  => 'root',
        'password'  => '4lt4m1r4*',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ],

    /* Session configuration */
    'session-time' => 10, // hours
    'session-name' => 'application-auth',

    /* Secret key */
    'secret-key' => '@asd9ws.w6*',

    /* Environment */
    'environment' => 'dev', // Options: dev, prod, stop

    /* Timezone */
    'timezone' => 'America/Lima',

    /* Cache */
    'cache' => false
];