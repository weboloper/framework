<?php

namespace App\Admin\Controllers;

use Phalcon\Mvc\Dispatcher;
use Components\Clarity\Support\Phalcon\Mvc\Controller as BaseController;

use Components\Model\Posts;
use Components\Model\Terms;

use Components\Model\Services\Service\User as userService;
use Components\Model\Services\Service\Post as postService;
use Components\Model\Services\Service\Term as termService;


class Controller extends BaseController
{
	protected $jsonResponse = false;
   
    public $jsonMessages = [];

	protected $statusCode = 200;

    public function onConstruct()
    {
        parent::onConstruct();
        $this->view->setVars([
            'tab'               => '',
            'postTypes'         => Posts::POST_TYPES ,
            'termTypes'         => Terms::TERM_TYPES ,
        ]);

        $this->userService = new userService;
        $this->termService = new termService;
        $this->postService = new postService;
    }

    public function initialize()
    {
    	$this->request = request();
    	$this->response = response();

        $base_path = __DIR__.'/../';
         
        $this->view->setViewsDir($base_path.'/views');

        $this->middleware('permission');
    }


    /**
     * Check if we need to throw a json response. For ajax calls.
     *
     * @return bool
     */
    public function isJsonResponse()
    {
        return $this->jsonResponse;
    }
    /**
     * Set a flag in order to know if we need to throw a json response.
     *
     * @return $this
     */
    public function setJsonResponse()
    {
        $this->jsonResponse = true;
        return $this;
    }
    /**
     * After execute route event
     *
     * @param Dispatcher $dispatcher
     */
    public function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isAjax() && $this->isJsonResponse()) {
            $this->view->disable();
            $this->response->setContentType('application/json', 'UTF-8');
            $data = $dispatcher->getReturnedValue();
            if (is_array($data)) {
                $this->response->setJsonContent($data);
            }
            echo $this->response->getContent();
        }
    }




   

}
