<?php

return [
    'testing' => [
        'ensure_pages_exist' => true,
    ],

    'ssr' => [
        'enabled' => true,
        'url' => 'http://127.0.0.1:13714',
        'bundle' => base_path('bootstrap/ssr/ssr.js'),
    ],
];