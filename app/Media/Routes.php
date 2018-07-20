<?php


Route::add('/media/upload', [
    'controller' => 'Index',
    'action' => 'upload',
]);

Route::add( '/media/browser', [
    'controller' => 'Index',
    'action' => 'browser',
]);


Route::add( '/media/list', [
    'controller' => 'Index',
    'action' => 'list',
]);

 