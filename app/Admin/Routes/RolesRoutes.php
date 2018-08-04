<?php

namespace App\Admin\Routes;

class RolesRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'namespace'  => 'App\Admin\Controllers',
            'controller' => 'Roles',
        ]);

        $this->setPrefix('/admin/roles');

        # url as roles/index
        $this->addGet('', [
            'action' => 'index'
        ]);

         # url as roles/create
        $this->addGet('/create', [
            'action' => 'create'
        ]);

        # url as roles/store
        $this->addPost('/store', [
            'action' => 'store'
        ]);

        # url as roles/1/show
        $this->addGet('/{id}/edit', [
            'action' => 'edit'
        ]);

        # url as roles/1/update
        $this->addPost('/{id}/update', [
            'action' => 'update'
        ]);

        # url as roles/1/delete
        $this->addPost('/{id}/delete', [
            'action' => 'delete'
        ]);
    }

}
