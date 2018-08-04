<?php

namespace App\Admin\Controllers;

use Components\Model\Roles;
use Components\Forms\RolesForm;
use Components\validation\RolesValidator;


class RolesController extends Controller
{
    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function index()
    {   
        $objects = Roles::find();
        return view('roles.index')->withObjects($objects);
    }

    public function create()
    {
        return view('roles.edit')
            ->with('form', new RolesForm() );
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

            $validator = new RolesValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(TermsValidator::toHtml($validation));
            }

 
            $role = new Roles;

            $success = $role->create([
                'name'  => $inputs['name'], 
                'description' => $inputs['description'] ,
            ]);

            if ($success === false) {
                throw new Exception(
                    'It seems we can\'t create a role, '.
                    'please check your access credentials!'
                );
            }
                

            return redirect()->to('admin/roles' )
                ->withSuccess("Role has been created");

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
         if (!$object = Roles::findFirstById($id)) {
            return redirect()->to( url("admin/roles"))->withError("Object  not found");
        }

 
        return view('roles.edit')
            ->with('form', new RolesForm($object) )
            ->with('object', $object );
   
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

            $validator = new RolesValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url('admin/roles/'. $id. '/edit'))
                    ->withError(PostsValidator::toHtml($validation));
            }


            if (!$object = Roles::findFirstById($id)) {
                return redirect()->to( url("admin/roles"))->withError("Object  not found");
            }

            $object->assign(
                $inputs,
                null,
                [
                    "name",
                    "description",
                ]
            );
 
        
            if ($object->save() === false) {
                foreach ($object->getMessages() as $message) {
                    flash()->session()->error($message);
                }
            } else {

                return redirect()->to(url('admin/roles/'. $id. '/edit'))
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
            // ...
        }
    }

}
