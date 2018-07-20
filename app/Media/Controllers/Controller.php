<?php

namespace App\Media\Controllers;

use Components\Clarity\Support\Phalcon\Mvc\Controller as BaseController;
use Phalcon\Mvc\Dispatcher;


class Controller extends BaseController
{
	protected $jsonResponse = false;
   
    public $jsonMessages = [];

	protected $statusCode = 200;

	public function initialize()
    {
    	$this->request = request();
    	$this->response = response();

        $base_path = __DIR__.'/../';
         
        $this->view->setViewsDir($base_path.'/views');
        
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
