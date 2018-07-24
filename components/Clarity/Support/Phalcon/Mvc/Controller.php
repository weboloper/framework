<?php
/**
 * PhalconSlayer\Framework.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

/**
 */
namespace Components\Clarity\Support\Phalcon\Mvc;

use Phalcon\Config;
use League\Tactician\CommandBus;
use Components\Clarity\Support\Phalcon\Http\Middleware;
use Phalcon\Mvc\Controller as BaseController;
use Components\Model\PostMeta;


class Controller extends BaseController
{   
    public function onConstruct()
    {   
        $this->tag->setTitle( $this->config->app->app->name );
        $this->view->setVars([
            'user'          => auth()->user() ,
            'app'           => $this->config->app->app,
            'action'        => di()->get('router')->getActionName(),
            'controller'    => di()->get('router')->getControllerName(),
            // 'googleAnalytic'=> $this->config->googleAnalytic
        ]);
    }

    public function middleware($alias, $options = [])
    {
        $middlewares = [];

        # get previously assigned aliases
        if (di()->has('middleware_aliases')) {
            $middlewares = di()->get('middleware_aliases')->toArray();
        }

        $append_alias = true;
        $action_name = dispatcher()->getActionName();

        if (isset($options['only'])) {
            if (in_array($action_name, $options['only']) === false) {
                $append_alias = false;
            }
        }

        if (isset($options['except'])) {
            if (in_array($action_name, $options['except'])) {
                $append_alias = false;
            }
        }

        if ($append_alias === true) {
            $middlewares[] = $alias;
        }

        di()->set('middleware_aliases', function () use ($middlewares) {
            return new Config($middlewares);
        });
    }

    public function beforeExecuteRoute()
    {
        # call the initialize to work with the middleware()
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }

        $this->middlewareHandler();
    }

    private function middlewareHandler()
    {
        if (di()->has('middleware_aliases') === false) {
            return;
        }

        # get all the middlewares in the config/app.php
        $middleware = new Middleware(config()->app->middlewares);

        $instances = [];
        $aliases = di()->get('middleware_aliases')->toArray();

        foreach ($aliases as $alias) {
            $class = $middleware->get($alias);
            $instances[] = new $class;
        }

        # register all the middlewares
        $command_bus = new CommandBus($instances);
        $command_bus->handle($this->request);
    }



    /**
     * To has a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function has_meta($objectId, $meta_key)
    {   
        $meta = PostMeta::find(
            [
                'meta_key = :meta_key: AND  post_id = :post_id: ',
                'bind'       => [
                    'meta_key' => $meta_key,
                    'post_id' => $objectId
                ]
            ]
        );

        return $meta->valid() ? $meta : false; 
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
                    'type'    => 'warning',
                    'content' => 'Object not found!'
                ];
                return $this->jsonMessages;
            }
            $object->delete();

 
            $this->jsonMessages['messages'][] = [
                'type'    => 'success',
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
    public function add_meta_________($objectId)
    {
        # process the request which it must be post and ajax request
        if (request()->isPost()  && request()->isAjax()) {
            
            $this->setJsonResponse();

            $metaKey = request()->getPost('meta_key', ['striptags', 'trim' , 'alphanum'] );
            $metaValue  = request()->getPost('meta_value', ['striptags', 'trim' , 'string']  );
            
            if( !$metaKey || !$metaValue ){
                $this->jsonMessages['messages'][] = [
                        'type'    => 'warning',
                        'content' => 'File not allowed'
                    ];
                return $this->jsonMessages;
            }

            if($metaKey == 'thumbnail'){

                if (filter_var( $metaValue , FILTER_VALIDATE_URL) === FALSE) {
                     $this->jsonMessages['messages'][] = [
                        'type'    => 'warning',
                        'content' => 'File not allowed'
                    ];
                    return $this->jsonMessages;
                }

                if($old_thumbnails = $this->has_meta($objectId , 'thumbnail'))
                {
                    foreach ( $old_thumbnails as $meta) {
                        $meta->delete();
                    }

                }
            }

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
