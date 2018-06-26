<?php

namespace App\Admin\Controllers;

use Phalcon\Mvc\Dispatcher;
use Components\Clarity\Support\Phalcon\Mvc\Controller as BaseController;

use Components\Model\PostMeta;
use Components\Model\Posts;
use Components\Model\Terms;

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
    }

    public function initialize()
    {
    	$this->request = request();
    	$this->response = response();

      

        // $this->middleware('permission');
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




    /**
     * To delete a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function delete_meta()
    {
        # process the request which it must be post and ajax request
        if (request()->isPost()  && request()->isAjax()) {
            
            $objectId = request()->getPost('object-id', 'int');
            $object   = request()->getPost('object', 'alphanum');
            
            $this->setJsonResponse();

            $object = PostMeta::findFirstByMeta_id($objectId);

            if(!$object) {
                $this->jsonMessages['messages'][] = [
                    'type'    => 'danger',
                    'content' => 'Object not found!'
                ];
                return $this->jsonMessages;
            }
            $object->delete();

 
            $this->jsonMessages['messages'][] = [
                'type'    => 'danger',
                'content' => 'Object has been deleted!'
            ];
            return $this->jsonMessages;
             

        }
    }

    /**
     * To add a record
     *
     * @param $id The id to be add
     *
     * @return void
     */
    public function add_meta($objectId)
    {
        # process the request which it must be post and ajax request
        if (request()->isPost()  && request()->isAjax()) {
            
            $metaKey = request()->getPost('meta_key', ['striptags', 'trim' , 'alphanum'] );
            $metaValue  = request()->getPost('meta_value', ['striptags', 'trim' , 'string']  );
            
            
            $object = new PostMeta();
            $object->setPostId($objectId);
            $object->setMetaKey($metaKey);
            $object->setMetaValue($metaValue);
            
            if (!$object->save()) {
                 
                foreach ($object->getMessages() as $m) {
                     return "<tr><td class='text-danger'>There is an error: ".$m->getMessage()."</td></tr>";
                }
                return $this->jsonMessages;
                return false;
            }

            

            // if(!$object) {
            //     $this->setJsonResponse();

            //     $this->jsonMessages['messages'][] = [
            //         'type'    => 'danger',
            //         'content' => 'Object not found!'
            //     ];
            //     return $this->jsonMessages;
            // }

            $lastInsertId = $object->meta_id;
           

            return '<tr><td>'. $metaKey .'</td>
                    <td>'. $metaValue .'</td>
                    <td><a href="#" 
                        class="delete-meta-btn" 
                        data-object-id="'. $lastInsertId .'"
                        data-object="postMeta"><i class="fas fa-trash"></i></a></td></tr>';

        }
    }

}
