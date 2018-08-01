<?php

namespace App\Admin\Routes;

class TermsRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'namespace'  => 'App\Admin\Controllers',
            'controller' => 'Terms',
        ]);

        $this->setPrefix('/admin/terms');

        # url as terms/index
        $this->addGet('', [
            'action' => 'index'
        ]);

        # url as terms/store
        $this->addGet('/create', [
            'action' => 'create'
        ]);

        # url as terms/store
        $this->addPost('/store', [
            'action' => 'store'
        ]);

        # url as terms/1/edit
        $this->addGet('/{id}/edit', [
            'action' => 'edit'
        ]);

        # url as terms/1/update
        $this->addPost('/{id}/update', [
            'action' => 'update'
        ]);

        # url as terms/1/delete
        $this->addPost('/{id}/delete', [
            'action' => 'delete'
        ]);
    }

}
