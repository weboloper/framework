<?php

Route::addGet('/admin', [
    'controller' => 'Posts',
    'action' => 'home',
]);
Route::mount(new App\Admin\Routes\PostsRoutes);
