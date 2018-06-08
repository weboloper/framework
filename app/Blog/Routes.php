<?php
Route::addGet('/', [
    'controller' => 'Main',
    'action' => 'index',
]);

Route::addGet('/posts', [
    'controller' => 'Posts',
    'action' => 'index',
]);

Route::addGet('/Posts/create', [
    'controller' => 'Posts',
    'action' => 'create',
]);


/*
+----------------------------------------------------------------+
|\ Organized Routes using RouteGroup                            /|
+----------------------------------------------------------------+
|
| You can group your routes by using route classes,
| mounting them to organize your routes
|
*/
Route::mount(new App\Blog\Routes\PostsRoutes);
