<?php
Route::addGet('/oauth', [
    'controller' => 'Oauth',
    'action' => 'showLoginForm',
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

Route::addGet('/', [
    'controller' => 'Oauth',
    'action' => 'index',
]);

Route::mount(new App\Oauth\Routes\OauthRoutes);