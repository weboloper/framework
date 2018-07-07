<?php


Route::add('/media/upload', [
    'controller' => 'Index',
    'action' => 'upload',
]);

Route::add( '/media/list.js', [
    'controller' => 'Index',
    'action' => 'list',
]);

 