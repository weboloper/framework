<?php
Route::addGet('/', [
    'controller' => 'Home',
    'action' => 'index',
]);

Route::addGet('/logged', [
    'controller' => 'Home',
    'action' => 'logged',
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
