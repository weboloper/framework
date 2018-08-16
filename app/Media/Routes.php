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



//
// Route::add('/media/delete_meta', [
//     'controller' => 'Index',
//     'action' => 'delete_meta',
// ]);

Route::addPost('/media/add_meta', [
    'controller' => 'Index',
    'action' => 'add_meta',
]);
Route::addPost('/media/delete_thumbnail', [
    'controller' => 'Index',
    'action' => 'delete_thumbnail',
]);


 