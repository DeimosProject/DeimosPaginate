<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$simple = (new \Deimos\Paginate\Paginate())
    ->arrayPager(range(1, 1000))
    ->limit(20)
    ->page(20);

var_dump($simple->currentPage());
var_dump($simple->itemCount());

var_dump($simple->currentItems());