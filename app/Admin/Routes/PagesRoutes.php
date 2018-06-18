<?php

namespace App\Admin\Routes;

class PagesRoutes extends RouteGroup
{
    public function initialize()
    {
        $this->setPaths([
            'controller' => 'Pages',
        ]);

        $this->setPrefix('/admin/pages');

        # url as posts/index
        $this->addGet('', [
            'action' => 'index'
        ]);

         # url as posts/store
        $this->addGet('/new', [
            'action' => 'new'
        ]);

        # url as posts/store
        $this->addPost('/store', [
            'action' => 'store'
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


    }

}
