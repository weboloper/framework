<?php

namespace App\Admin\Routes;

class PostsRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'controller' => 'Posts',
        ]);

        $this->setPrefix('/admin/posts');

        # url as posts/index
        $this->addGet('', [
            'action' => 'index'
        ]);

         # url as post/create
        $this->addGet('/create', [
            'action' => 'create'
        ]);

        # url as posts/1/publish
        $this->add('/{id}/publish', [
            'action' => 'publish'
        ]);

        # url as posts/store
        $this->addPost('/store', [
            'action' => 'store'
        ]);

        # url as posts/1/show
        $this->addGet('/{id}/view', [
            'action' => 'view'
        ]);

        # url as posts/1/show
        $this->addGet('/{id}/edit', [
            'action' => 'edit'
        ]);

        # url as posts/1/update
        $this->addPost('/{id}/update', [
            'action' => 'update'
        ]);

        # url as posts/1/delete
        $this->addPost('/{id}/delete', [
            'action' => 'delete'
        ]);

         # url as posts/1/delete
        $this->addPost('/{id}/delete_file', [
            'action' => 'delete_file'
        ]);

       

        //  # url as posts/grid
        // $this->add('/grid', [
        //     'action' => 'gridAction'
        // ]);

        // # url as posts/index
        // $this->addGet('/indexjquery', [
        //     'action' => 'indexjquery'
        // ]);

    }

}
