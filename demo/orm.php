<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$helper = new \Deimos\Helper\Helper();

$db = new \Deimos\Database\Database(new \Deimos\Slice\Slice($helper, [
    'adapter'  => 'mysql',
    'database' => 'test',
    'username' => 'root',
    'password' => 'root'
]));

$orm = new \Deimos\ORM\ORM($helper, $db);

$userQuery = $orm->repository('user');

$simple = (new \Deimos\Paginate\Paginate())
    ->queryPager($userQuery)
    ->limit(1)
    ->page(-1);

var_dump($simple->currentPage());
var_dump($simple->itemCount());

var_dump($simple->currentItems(false));
