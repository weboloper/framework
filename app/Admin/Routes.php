<?php

Route::addGet('/admin', [
    'controller' => 'Posts',
    'action' => 'home',
]);
 
Route::addPost('/admin/posts/delete_meta', [
    'controller' => 'Posts',
    'action' => 'delete_meta',
]);

Route::addPost('/admin/posts/{id}/add_meta', [
    'controller' => 'Posts',
    'action' => 'add_meta',
]);


Route::add('/admin/getterms', [
    'controller' => 'Posts',
    'action' => 'getterms',
]);



Route::mount(new App\Admin\Routes\PostsRoutes);
Route::mount(new App\Admin\Routes\PagesRoutes);
Route::mount(new App\Admin\Routes\UsersRoutes);
Route::mount(new App\Admin\Routes\TermsRoutes);
