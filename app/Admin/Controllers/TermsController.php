<?php

namespace App\Admin\Controllers;

use Components\Model\Terms;
use Components\Forms\TermsForm;

use Components\validation\TermsValidator;
use Components\Utils\Slug;

use Exception;


class TermsController extends Controller
{   
    public $taxonomy;

    public function initialize()
    {   
        parent::initialize();
        
        $taxonomy =  request()->getQuery('taxonomy',  ['striptags', 'trim' , 'alphanum']  , 'tag');

        if( !array_key_exists( $taxonomy , Terms::TERM_TYPES)){
            return redirect()
            ->to(
                url("admin")
            )
            ->withError("Object type [" . $taxonomy ."] not found");
        }

        $this->taxonomy =  $taxonomy ;
        $this->view->tab =  $taxonomy;
        $this->objectType = Terms::TERM_TYPES[  $this->taxonomy ];
    }
    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function index()
    {
 
        $objects = Terms::find(['taxonomy = :taxonomy:', 'bind' => [ 'taxonomy' =>  $this->taxonomy  ]]);
        
        return view('terms.index')->withObjects( $objects )->with( 'objectType', $this->objectType );
    }

    /**
     * To store a new record
     *
     * @return void
     */
    public function create()
    {   
        $objects = Terms::find(['taxonomy = :taxonomy: and parent_id = 0 ', 'bind' => [ 'taxonomy' =>  $this->taxonomy  ]]);

        return view('terms.edit')
            ->with('form', new TermsForm() )
            ->with('objects', $objects )
            ->with('objectType', $this->objectType );
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
        if (!$object = Terms::findFirstByTerm_id($id)) {
            return redirect()->to( url("admin/terms"))->withError("Object  not found");
        }

        $objects = Terms::find(['taxonomy = :taxonomy: and parent_id = 0 ', 'bind' => [ 'taxonomy' =>   $object->taxonomy   ]]);

        return view('terms.edit')
            ->with('form', new TermsForm($object) )
            ->with('object', $object )
            ->with('objects', $objects )
            ->with('tab', $object->taxonomy )
            ->with('objectType', Terms::TERM_TYPES[ $object->taxonomy  ]  );
 
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

            $validator = new TermsValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(TermsValidator::toHtml($validation));
            }

            if(! $inputs['parent']  ){
                $inputs['parent'] = 0 ;
            }
            

            $term = new Terms;

            $success = $term->create([
                'name'  => $inputs['name'], 
                'slug' => $inputs['slug'] ,
                'taxonomy' => $inputs['taxonomy'] ,
                'description' => $inputs['description'] ,
                'parent' => $inputs['parent'] ,
            ]);

            if ($success === false) {
                throw new Exception(
                    'It seems we can\'t create a term, '.
                    'please check your access credentials!'
                );
            }
                

            return redirect()->to('admin/terms/?taxonomy=' . $inputs['taxonomy'] )
                ->withSuccess("Term has been created");

        }
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

            $validator = new TermsValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url('admin/terms/'. $id. '/edit'))
                    ->withError(PostsValidator::toHtml($validation));
            }


            if (!$object = Terms::findFirstByTerm_id($id)) {
                return redirect()->to( url("admin/terms"))->withError("Object  not found");
            }

            $object->assign(
                $inputs,
                null,
                [
                    "name",
                    // "slug", // because its disabled
                    "taxonomy",
                    "description",
                    "parent_id",
                ]
            );

            $this->getUniqueSlug($object , $inputs['slug']);

 
        
            if ($object->save() === false) {
                foreach ($object->getMessages() as $message) {
                    flash()->session()->error($message);
                }
            } else {

                return redirect()->to(url('admin/terms/'. $id. '/edit'))
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
        if (request()->isPost() && request()->isAjax()) {

            $this->setJsonResponse();

            $object = Terms::findFirstByTerm_id($id);

            if(!$object) {
                $this->response->setStatusCode(404);
                $this->jsonMessages['messages'][] = [
                    'type'    => 'error',
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


    public function checkSlug($slug, $type )
    {
        return Terms::findFirst([
            'taxonomy = :type: AND slug = :slug:',
            'bind' => [
                'type' => $type,
                'slug' => $slug
            ]
        ]);
    }

    public function getUniqueSlug(Terms $object , $slug)
    {   
        $slug = Slug::generate($slug);

        if($exists = $this->checkSlug($slug , $object->taxonomy ))
        {      

            if(  $exists->taxonomy == $object->taxonomy AND $exists->term_id  != $object->term_id     ) {

                return $this->getUniqueSlug($object, $slug."-2");

            }
        }

        $object->setSlug($slug);

        return $object;
 

    }


}
