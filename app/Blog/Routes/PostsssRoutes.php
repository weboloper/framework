<?php

namespace \app\Blog\Routes;

class PostsssRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'namespace'  => '\app\Blog\Controllers',
            'controller' => 'Postsss',
        ]);

        $this->setPrefix('/postsss');

        # url as postsss/index
        $this->addGet('/index', [
            'action' => 'index'
        ]);

        # url as postsss/store
        $this->addPost('/store', [
            'action' => 'store'
        ]);

        # url as postsss/1/show
        $this->addGet('/{id}/show', [
            'action' => 'show'
        ]);

        # url as postsss/1/update
        $this->addPost('/{id}/update', [
            'action' => 'update'
        ]);

        # url as postsss/1/delete
        $this->addPost('/{id}/delete', [
            'action' => 'delete'
        ]);
    }

}
