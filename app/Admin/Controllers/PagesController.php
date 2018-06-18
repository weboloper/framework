<?php

namespace App\Admin\Controllers;
use Components\Model\Posts;
use Phalcon\Mvc\View;


use Components\Forms\PostsForm;
use Components\validation\PostsValidator;
use Components\Utils\Slug;
// use Phalcon\Filter;

class PagesController extends Controller
{   

 
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
        $status = request()->getQuery("status");

        if($status) {
            $objects = Posts::find([
            'type = "page" AND status = :status:',
            'bind' => [
                'status' => $status 
                ]
            ]);
        }else {
            $objects = Posts::find("type = 'page' and status !='trash' ");
        }

         return view('admin.pages.index')->withObjects( $objects );
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
        $object->setExcerpt(' ');
        $object->setType('page');
        $object->setUserId(auth()->getUserId());
        if ($object->save() === false) {
                foreach ($object->getMessages() as $message) {
                    // die(var_dump($message));
                }
            }

        return view('admin.pages.edit')
            ->with('form', new PostsForm($object) )
            ->withObject($object);
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
            $this->flashSession->error(t("Posts doesn't exist."));
            return $this->currentRedirect();
        }

        return view('admin.pages.edit')
            ->with('id', $id)
            ->with('form', new PostsForm($object) )
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
                return redirect()->to(url('admin/pages/'. $id. '/edit'))
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
