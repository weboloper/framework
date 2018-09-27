<?php

namespace App\Admin\Controllers;
use Components\Model\Model;
use Components\Model\Posts;
use Components\Model\Terms;
use Components\Model\Services\Service\Meta as metaService;
use Phalcon\Mvc\View;


use Components\Forms\PostsForm;
use Components\validation\PostsValidator;
use Components\Utils\Slug;
// use Phalcon\Filter;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

use claviska\SimpleImage;

use \Exception;

class PostsController extends Controller
{   

    public $type;
    public $objectType;
    
    public function initialize()
    {   
        parent::initialize();

        $type =  request()->getQuery('type',  ['striptags', 'trim' , 'alphanum']   , Posts::DEFAULT_POST_TYPE['slug'] );

        if( !array_key_exists( $type , Posts::POST_TYPES)){
            return redirect()->to( url("admin"))->withError("Object type [" . $type ."] not found");
        }

        $this->type = $type;
        $this->view->tab = $this->type;        
        $this->objectType = Posts::POST_TYPES[ $this->type ];        
        $this->view->objectType = $this->objectType;  

        $this->default_model = \Components\Model\Posts::class;      
        $this->view->terms_array = [];      
        $this->view->post_terms = [];

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

        if($this->type != 'attachment')
        {
            if($status) {
                $params['status']  = $status ;
                $statusConditions = 'p.status = :status:';
                $itemBuilder->andWhere($statusConditions);
            }else {

                $statusConditions = ' p.status  != "trash" ';
                // $statusConditions = ' p.status   !IN("draft", "trash")  ';
                $itemBuilder->andWhere($statusConditions);
            }
        }else {
            $statusConditions = ' p.status != "trash"  ';
            $itemBuilder->andWhere($statusConditions);
             
        }

        $objects = $itemBuilder->getQuery()->execute($params);

        // if($this->type == 'attachment') {
        //     return view('attachments.index')->withObjects( $objects )->with( 'objectType', $this->objectType );
        // }
        return view('posts.index')->withObjects( $objects );
    }

    /**
     * To show an output based on the requested ID
     *
     * @param $id
     *
     * @return mixed
     */
    public function create()
    {   

        if($this->type == 'attachment')
        {
            return view('posts.browser');
        }else {
            $terms_array = [];
            foreach ( Posts::POST_TYPES[$this->type]['terms'] as $key   ) {
                $terms  = Terms::find([   'taxonomy = :type:  ' , 'bind' => ['type' => $key ]]) ; 
                $terms_array[$key] = $terms ;

            }
            return view('posts.create')->with('form', new PostsForm() )->with( 'terms_array', $terms_array );
        }

        $object = new Posts();
        $object->setTitle(' ');
        $object->setSlug('');
        $object->setBody('');
        $object->setType($this->type);
        $object->setExcerpt(' ');
        $object->setStatus( Posts::STATUS_DRAFT );
        $object->setUserId(auth()->getUserId());
        if ($object->save() === false) {
            foreach ($object->getMessages() as $message) {
                // die(var_dump($message));
            }
        }

        return redirect()->to(url('admin/posts/'. $object->id. '/edit'));

  
    }
 

    /**
     * To store a new record
     *
     * @return void
     */
    public function store()
    {
        if (request()->isPost()) {
             // do some stuff ...
            $inputs = request()->get();

            $validator = new PostsValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(PostsValidator::toHtml($validation));
            }

            foreach ($this->default_model::COLUMN_MAP as $key ) {
                if(!array_key_exists( $key, $inputs )) {
                    $inputs[$key] = '';
                }
            }

            $slug_root = ( $inputs['slug'] == '' ) ?  $inputs['title'] : $inputs['slug'];
            $uniqueSlugForObject = $this->postService->getUniqueSlug( $slug_root , $this->type , $object = null );

            $object = new Posts;

            $success = $object->create([
                'title'    => $inputs['title'], 
                'slug'     => $uniqueSlugForObject ,
                'body'     => $inputs['body'] ,
                'excerpt'  => $inputs['excerpt'] ,
                'status'   => $inputs['status'] ,

             ]);

            if ($success === false) {

                throw new Exception(
                    $object->getMessages()[0]
                );
            }
                

            return redirect()->to('admin/posts/' . $object->id  . "/edit" )
                ->withSuccess("Post has been created. You may publish it");

        }
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
            return redirect()->to( url("admin/posts"))->withError("Object  not found");
        }
    
        $terms_array = [];
        foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
            $terms  = Terms::find([   'taxonomy = :type:  ' , 'bind' => ['type' => $key ]]) ; 
            $terms_array[$key] = $terms ;

        }

        $post_terms = [];
        foreach ($object->getTerms()->toArray()  as $item ) {
            $post_terms[] =  $item['term_id'];
        }

        return view('posts.edit')
            ->with('id', $id)
            ->with('form', new PostsForm($object) )
            ->with( 'objectType', Posts::POST_TYPES[$object->getType()] )
            ->with( 'terms_array', $terms_array )
            ->with( 'post_terms', $post_terms )
            ->with( 'is_new', false )
            ->with( 'tab', $object->getType() )
            ->withObject($object);
    }

 
    public function view($id)
    {   
        if (!$object = Posts::findFirstById($id)) {
            return redirect()->to( url("admin/posts"))->withError("Object  not found");
        }
 
 
        return view('posts.view')
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

            if (!$object = Posts::findFirstById($id)) {
                return redirect()->to( url("admin/posts"))->withError("Object  not found");
            }

            $object->assign(
                $inputs,
                null,
                [
                    "title",
                    "body",
                    "status",
                ]
            );

            $slug_root = ( $inputs['slug'] == '' ) ?  $inputs['title'] : $inputs['slug'];
            $uniqueSlugForObject = $this->postService->getUniqueSlug( $slug_root , $this->type , null );

            $object->setSlug($uniqueSlugForObject);

            if ( request()->getPost('publish')) {
                if( !$inputs['title'] ) {
                    return redirect()->to(url('admin/posts/'. $id. '/edit'))
                    ->withError('Post must have a title' );
                }
                $object->setStatus(  Posts::STATUS_PUBLISH  );
             }else {
                
                $object->setStatus( $inputs['status'] );
            }

            $terms_array = [];
            foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
                $terms = $this->request->getPost('term_'.$key , null);

                if(!is_null($terms) ) { $terms_array = array_merge($terms_array, $terms); }
            }
        
        
            if ($object->save() === false) {
                foreach ($object->getMessages() as $message) {
                    flash()->session()->error($message);
                }
            } else {

                $this->termService->saveTermsInPosts($terms_array, $object);

                foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
                    di()->get('viewCache')->delete("metabox".$key);
                }

            
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
            $this->setJsonResponse();
            $object = Posts::findFirstById($id);

            if(!$object) {
                $this->response->setStatusCode(404);
                $this->jsonMessages['messages'][] = [
                    'type'    => 'warning',
                    'content' => 'Object not found!'
                ];
                return $this->jsonMessages;
            }
            $object->delete();

            $this->jsonMessages['messages'][] = [
                'type'    => 'success',
                'content' => 'Object sent to trash!'
            ];
            return $this->jsonMessages;
             

        }
    }


    


}
