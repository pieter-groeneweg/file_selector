<?php
return [
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'page#addFile', 'url' => '/add', 'verb' => 'POST'],
        ['name' => 'page#deleteFile', 'url' => '/delete', 'verb' => 'POST'],
    ]
];
