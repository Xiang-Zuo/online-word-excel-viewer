<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\App;

$params = [
    'rootDir' => __DIR__ . '/..'
];

if ($queryName = $_GET ? array_key_first($_GET) : false) {
    $params['queryName'] = $queryName;
} else {
    $params['queryName'] = 'home';
}

$app = new App();
echo $app->router($params);
