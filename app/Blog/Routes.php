<?php

Route::add('/{router}', [
    'controller' => 'Home',
    'action' => 'index',
])->beforeMatch(function ($uri, $route) {
    $uris = ['posts', 'users', 'oauth' , 'terms', 'search', 'admin'];
    if ($uri == '/' || in_array(ltrim($uri, '/'), $uris)) {
        return false;
    }
     return ! request()->isAjax();
});

Route::addGet('/', [
    'controller' => 'Posts',
    'action' => 'index',
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

