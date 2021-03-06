<?php

# for `serve` console command as htrouter
if (preg_match('/\.(?:png|jpg|jpeg|css|woff|woff2)$/', $_SERVER['REQUEST_URI'])) {
    return false;
}

require dirname(__DIR__) . '/bootstrap/start.php';

$kernel
    ->modules()
    ->run('media')
    ->render();
