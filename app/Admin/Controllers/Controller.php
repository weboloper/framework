<?php

namespace App\Admin\Controllers;

use Phalcon\Mvc\Dispatcher;
use Components\Clarity\Support\Phalcon\Mvc\Controller as BaseController;

use Components\Model\Posts;
use Components\Model\Terms;




class Controller extends BaseController
{
	
    public function onConstruct()
    {
        parent::onConstruct();
        $this->view->setVars([
            'tab'               => '',
            'postTypes'         => Posts::POST_TYPES ,
            'termTypes'         => Terms::TERM_TYPES ,
        ]);

        
    }

    public function initialize()
    {
    	$this->request = request();
    	$this->response = response();

        $base_path = __DIR__.'/../';
         
        $this->view->setViewsDir($base_path.'/views');

        $this->middleware('permission');
    }

 
   

}
