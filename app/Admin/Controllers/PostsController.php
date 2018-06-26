<?php

namespace App\Admin\Controllers;
use Components\Model\Model;
use Components\Model\Posts;
use Components\Model\Terms;
use Phalcon\Mvc\View;


use Components\Forms\PostsForm;
use Components\validation\PostsValidator;
use Components\Utils\Slug;
// use Phalcon\Filter;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class PostsController extends Controller
{   

    public $type;
    public $objectType;
    
    public function initialize()
    {   

        $type =  request()->getQuery('type',  ['striptags', 'trim' , 'alphanum']  , 'post');

        if( !array_key_exists( $type , Posts::POST_TYPES)){
            return view('admin.posts.error');
        }

        $this->view->tab = $type;        
        $this->type = $type;
        $this->objectType = Posts::POST_TYPES[$type];

    }
    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function home()
    {
        flash()->session()->success(
                'Welcome to Admin section.'
        );
        return view('admin.home');
    }

 
    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function index()
    {   
        // $type = request()->getQuery('type');
        $status = request()->getQuery("status", ['striptags', 'trim' , 'alphanum'] );
        $meta_key = request()->getQuery("meta_key", ['striptags', 'trim' , 'alphanum'] );
        $meta_value = request()->getQuery("meta_value", ['striptags', 'trim' ] );

        if ($meta_key ||  $meta_value) {
            $join = [
                'type'  => 'join',
                'model' => 'PostMeta',
                'on'    => 'r.post_id = p.id',
                'alias' => 'r'
            ];
            list($itemBuilder, $totalBuilder) =
                Model::prepareQueriesPosts($join, false, 999999999999999);
            $itemBuilder->groupBy(array('p.id'));

        } else {
            list($itemBuilder, $totalBuilder) =
                Model::prepareQueriesPosts( false , false, 999999999999999999);
        }
 
        $params = [];
        $params['type']  = $this->type ;
        $typeConditions = 'p.type = :type:';
        $itemBuilder->where($typeConditions);

        if($meta_key){
            $params['meta_key']  = $meta_key ;
            $meta_keyConditions = 'r.meta_key = :meta_key:';
            $itemBuilder->andWhere($meta_keyConditions);
        }

        if($meta_value){
            $params['meta_value']  = $meta_value ;
            $meta_valueConditions = 'r.meta_value = :meta_value:';
            $itemBuilder->andWhere($meta_valueConditions);
        }

        if($status) {
            $params['status']  = $status ;
            $statusConditions = 'p.status = :status:';
            $itemBuilder->andWhere($statusConditions);
        }

        $objects = $itemBuilder->getQuery()->execute($params);

        return view('admin.posts.index')->withObjects( $objects )->with( 'objectType', $this->objectType );
    }

    /**
     * To show an output based on the requested ID
     *
     * @param $id
     *
     * @return mixed
     */
    public function new()
    {   
        
        $object = new Posts();
        $object->setTitle(' ');
        $object->setSlug(' ');
        $object->setBody(' ');
        $object->setType($this->type);
        $object->setExcerpt(' ');
        $object->setUserId(auth()->getUserId());
        if ($object->save() === false) {
            foreach ($object->getMessages() as $message) {
                // die(var_dump($message));
            }
        }

        $terms_array = [];
        foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
            $terms  = Terms::find([   'taxonomy = :type: and parent = 0 ' , 'bind' => ['type' => $key ]]) ; 
            $terms_array[$key] = $terms ;

        }

        return view('admin.posts.edit')
            ->with('form', new PostsForm($object) )
            ->with( 'objectType', $this->objectType )
            ->with( 'terms_array', $terms_array )
            ->withObject($object);
    }


    /**
     * To show an output based on the requested ID
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {   
        if (!$object = Posts::findFirstById($id)) {
            $this->flashSession->error(t("Posts doesn't exist."));
            return $this->currentRedirect();
        }

        $terms_array = [];
        foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
            $terms  = Terms::find([   'taxonomy = :type: and parent = 0 ' , 'bind' => ['type' => $key ]]) ; 
            $terms_array[$key] = $terms ;

        }

        return view('admin.posts.edit')
            ->with('id', $id)
            ->with('form', new PostsForm($object) )
            ->with( 'objectType', Posts::POST_TYPES[$object->getType()] )
            ->with( 'terms_array', $terms_array )
            ->withObject($object);
    }

    /**
     * To update a record based on the requested ID
     *
     * @param $id
     *
     * @return void
     */
    public function update($id)
    {
        # process the post request
        if (request()->isPost()) {
            
            $inputs = request()->get();

            $validator = new PostsValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url('admin/posts/'. $id. '/edit'))
                    ->withError(PostsValidator::toHtml($validation));
            }
            $object = Posts::findFirstById($id);

            $object->assign(
                $inputs,
                null,
                [
                    "title",
                    // "slug",
                    "body",
                    "status",
                ]
            );

 
            if ( request()->getPost('savePost')) {
                $object->setStatus( $inputs['status'] );
             }else {
                if( !$inputs['title'] ) {
                    return redirect()->to(url('admin/posts/'. $id. '/edit'))
                    ->withError('Post must have a title' );
                }
                $object->setStatus( Posts::STATUS_PUBLISH );
            }

 
            $object->setSlug(Slug::generate(  $inputs['title'] ));

    
            if ($object->save() === false) {
                foreach ($object->getMessages() as $message) {
                    flash()->session()->error($message);
                }
            } else {
                return redirect()->to(url('admin/posts/'. $id. '/edit'))
                    ->withSuccess('Object updated successfully!');
            }

 


        }
    }

    /**
     * To delete a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function delete($id)
    {
        # process the request which it must be post and ajax request
        if (request()->isPost()  && request()->isAjax()) {
            
            
            $object = Posts::findFirstById($id);

            if(!$object) {
                $this->jsonMessages['messages'][] = [
                    'type'    => 'danger',
                    'content' => 'Object not found!'
                ];
                return $this->jsonMessages;
            }
            $object->delete();

            $this->setJsonResponse();

            $this->jsonMessages['messages'][] = [
                'type'    => 'danger',
                'content' => 'Object has been deleted!'
            ];
            return $this->jsonMessages;
             

        }
    }

}
