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

use claviska\SimpleImage;

class PostsController extends Controller
{   

    public $type;
    public $objectType;
    
    public function initialize()
    {   
        parent::initialize();

        $type =  request()->getQuery('type',  ['striptags', 'trim' , 'alphanum']  , 'post');


        if( !array_key_exists( $type , Posts::POST_TYPES)){
            return redirect()->to( url("admin"))->withError("Object type [" . $type ."] not found");
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
        }else {

            $statusConditions = 'p.status != "trash" ';
            $itemBuilder->andWhere($statusConditions);
        }

        $objects = $itemBuilder->getQuery()->execute($params);

        // if($this->type == 'attachment') {
        //     return view('attachments.index')->withObjects( $objects )->with( 'objectType', $this->objectType );
        // }
        return view('posts.index')->withObjects( $objects )->with( 'objectType', $this->objectType );
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

        if($this->type == 'attachment')
        {
            return view('posts.upload');
        }
        
        $object = new Posts();
        $object->setTitle(' ');
        $object->setSlug(' ');
        $object->setBody(' ');
        $object->setType($this->type);
        $object->setExcerpt(' ');
        $object->setStatus( Posts::STATUS_DRAFT );
        $object->setUserId(auth()->getUserId());
        if ($object->save() === false) {
            foreach ($object->getMessages() as $message) {
                // die(var_dump($message));
            }
        }

        $terms_array = [];
        foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
            $terms  = Terms::find([   'taxonomy = :type: and parent_id = 0 ' , 'bind' => ['type' => $key ]]) ; 
            $terms_array[$key] = $terms ;

        }
        
        // $text =  lang()->get('responses/alert.sure_to_leave')

        // $this->assets->addInlineJs('$(window).bind("beforeunload",function(){return"Are you sure you want to leave?"});');
        // $this->assets->addInlineJs('swal("Good job!", "You clicked the button!", "success");');

        

        return view('posts.edit')
            ->with('form', new PostsForm($object) )
            ->with( 'objectType', $this->objectType )
            ->with( 'terms_array', $terms_array )
            ->with( 'post_terms', array() )
            ->with( 'is_new', true )
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
            return redirect()->to( url("admin/posts"))->withError("Object  not found");
        }

        if($this->type == 'attachment')
        {
            return view('posts.edit_file')->withObject($object);
        }
        

        $terms_array = [];
        foreach ( Posts::POST_TYPES[$object->getType()]['terms'] as $key   ) {
            $terms  = Terms::find([   'taxonomy = :type: and parent_id = 0 ' , 'bind' => ['type' => $key ]]) ; 
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
                    // "slug", // because its disabled
                    "body",
                    "status",
                ]
            );

            $this->getUniqueSlug($object , $inputs['title']);

            // $object->setGuid(  url()->get((  $slug ))   );

            if ( request()->getPost('savePost')) {
                $object->setStatus( $inputs['status'] );
             }else {
                if( !$inputs['title'] ) {
                    return redirect()->to(url('admin/posts/'. $id. '/edit'))
                    ->withError('Post must have a title' );
                }
                $object->setStatus( Posts::STATUS_PUBLISH );
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

                return redirect()->to(url('admin/posts/'. $id. '/edit'))
                    ->withSuccess('Object updated successfully!');
            }

 


        }
    }

    public function checkSlug($slug, $type )
    {
        return Posts::findFirst([
            'type= :type: AND slug = :slug:',
            'bind' => [
                'type' => $type,
                'slug' => $slug
            ]
        ]);
    }

    public function getUniqueSlug(Posts $object , $slug)
    {   
        $slug = Slug::generate($slug);

        if($exists = $this->checkSlug($slug , $object->getType() ))
        {      
            // $options = [];
            // $options['exists']['id'] = $exists->getId();
            // $options['exists']['type'] = $exists->getType();

            // $options['object']['id'] = $object->getId();
            // $options['object']['type'] = $object->getType();

            // die(var_dump(  $options ));

            if(  $exists->getType() == $object->getType() AND $exists->getId() != $object->getId()     ) {

                return $this->getUniqueSlug($object, $slug."-2");

            }
        }

        $object->setSlug($slug);

        return $object;
 

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
                'content' => 'Object has been deleted!'
            ];
            return $this->jsonMessages;
             

        }
    }

}
