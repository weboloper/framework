<?php

namespace App\Blog\Routes;

class PostsRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'controller' => 'Posts',
        ]);

        $this->setPrefix('/posts');

        # url as posts/index
        $this->addGet('/index', [
            'action' => 'index'
        ]);

        # url as posts/store
        $this->addPost('/store', [
            'action' => 'store'
        ]);

        # url as posts/1/show
        $this->addGet('/{id}/show', [
            'action' => 'show'
        ]);

        # url as posts/1/update
        $this->addPost('/{id}/update', [
            'action' => 'update'
        ]);

        # url as posts/1/delete
        $this->addPost('/{id}/delete', [
            'action' => 'delete'
        ]);
    }

}
