<?php

namespace App\Admin\Routes;

class UsersRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'namespace'  => 'App\Admin\Controllers',
            'controller' => 'Users',
        ]);

        $this->setPrefix('/admin/users');

        # url as users/index
        $this->addGet('', [
            'action' => 'index'
        ]);

        # url as users/new
        $this->addGet('/new', [
            'action' => 'new'
        ]);

        # url as users/new
        $this->add('/store', [
            'action' => 'store'
        ]);


        # url as users/1/edit
        $this->addGet('/{id}/edit', [
            'action' => 'edit'
        ]);

        # url as users/1/update
        $this->addPost('/{id}/update', [
            'action' => 'update'
        ]);

        # url as users/1/delete
        $this->addPost('/{id}/delete', [
            'action' => 'delete'
        ]);

        # url as users/id/changepassword
        $this->add('/{id}/edit/password', [
            'action' => 'changepassword'
        ]);

         # url as posts/grid
        $this->add('/grid', [
            'action' => 'gridAction'
        ]);

    }

}
