<?php

Route::addGet('/admin', [
    'controller' => 'Index',
    'action' => 'home',
]);
 
Route::add('/admin/posts/delete_meta', [
    'controller' => 'Posts',
    'action' => 'delete_meta',
]);

Route::addPost('/admin/posts/add_meta', [
    'controller' => 'Posts',
    'action' => 'add_meta',
]);
Route::addPost('/admin/posts/{id}/delete_thumbnail', [
    'controller' => 'Posts',
    'action' => 'delete_thumbnail',
]);



Route::add('/admin/getterms', [
    'controller' => 'Posts',
    'action' => 'getterms',
]);


Route::add('/admin/filemanager', [
    'controller' => 'Index',
    'action' => 'filemanager',
]);


Route::mount(new App\Admin\Routes\PostsRoutes);
Route::mount(new App\Admin\Routes\UsersRoutes);
Route::mount(new App\Admin\Routes\TermsRoutes);
// Route::mount(new App\Admin\Routes\RolesRoutes);
