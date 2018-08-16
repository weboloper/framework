<?php

namespace App\Oauth\Controllers;

use Components\Clarity\Support\Phalcon\Mvc\Controller as BaseController;

class Controller extends BaseController
{

	public function initialize()
    {
    	$this->request = request();
    	$this->response = response();

        $base_path = __DIR__.'/../';
         
        $this->view->setViewsDir($base_path.'/views');

     }
    
}
