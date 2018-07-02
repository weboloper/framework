<?php

namespace App\Admin\Controllers;
use Phalcon\Mvc\View;
use Components\Model\Users;

use Components\Forms\UsersForm;
use Components\validation\RegistrationValidator;
use Components\validation\UsersEditValidator; 
use Components\validation\ResetpassValidator; 
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;

use Components\Model\Services\Service\User as userService;

class UsersController extends Controller
{   
   

    public $type;
    
    public function initialize()
    {   
        parent::initialize();
        $this->view->tab = 'users';
        

    }

    /**
     * View the starting index of this resource
     *
     * @return mixed
     */
    public function index()
    {   
        $objects = Users::find();
        return view('admin.users.index')->withObjects( $objects );
    }

    /**
     * To store a new record
     *
     * @return void
     */
    public function new()
    {
      
        return view('admin.users.new');
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

            $validator = new RegistrationValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(RegistrationValidator::toHtml($validation));
            }


            $token = bin2hex(random_bytes(100));
           
                $user = new Users;

                $success = $user->create([
                    'name'  => $inputs['name'], 
                    'email' => $inputs['email'],
                    'password' => security()->hash($inputs['password']),
                    'token' => $token,
                    'activated' => 1,
                ]);

                if ($success === false) {
                    throw new Exception(
                        'It seems we can\'t create an account, '.
                        'please check your access credentials!'
                    );
                }
                

            return redirect()->to('admin/users/'. $user->id . '/edit')
                ->withSuccess(lang()->get('responses/register.creation_success'));

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
        if (!$object = Users::findFirstById($id)) {
            $this->flashSession->error(t("Users doesn't exist."));
            return $this->currentRedirect();
        }

        $user_roles = [];
        foreach ($object->getRoles()->toArray()  as $item ) {
            $user_roles[] =  $item['id'];
        }


        return view('admin.users.edit')
            ->with('id', $id)
            ->with('form', new UsersForm($object) )
            ->with('user_roles', $user_roles )
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
            // ...
            $inputs = request()->get();

            $validator = new UsersEditValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(RegistrationValidator::toHtml($validation));
            }

            $object = Users::findFirstById($id);

            $object->assign(
                $inputs,
                null,
                [
                    "email",
                    // "slug",
                    "name",
                    "status",
                ]
            );

            $roles_array = $this->request->getPost('roles' , null);

            if ($object->save() === false) {
                foreach ($object->getMessages() as $message) {
                    flash()->session()->error($message);
                }
            } else {

                $this->userService->saveRolesInUsers($roles_array, $object);

                return redirect()->to(url('admin/users/'. $id. '/edit'))
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
           

            if(auth()->getUserId() == $id){
                $this->response->setStatusCode(401);
                $this->jsonMessages['messages'][] = [
                    'type'    => 'error',
                    'content' => 'You can not delete yourself'
                ];
                return $this->jsonMessages;
            }
 
            $object = Users::findFirstById($id);

            if(!$object) {
                $this->response->setStatusCode(404);
                $this->jsonMessages['messages'][] = [
                    'type'    => 'error',
                    'content' => 'Object not found!'
                ];
                return $this->jsonMessages;
            }

            $userposts = $this->postService->findByUser_id($id);

            if($userposts) {
                $this->response->setStatusCode(404);
                $this->jsonMessages['messages'][] = [
                    'type'    => 'warning',
                    'content' => 'Delete posts by user first!'
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
     * To store a new record
     *
     * @return void
     */
    public function changepassword($id)
    {

        # process the post request
        if (request()->isPost()) {
            // ...
            $inputs = request()->get();

            $validator = new ResetpassValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(ResetpassValidator::toHtml($validation));
            }

            $user = Users::findFirstById($id);

            try {
                $this->userService->assignNewPassword($user, $inputs['password']);
                return redirect()->to(url('admin/users/'. $id. '/edit'))
                ->withSuccess(lang()->get('responses/forgetpass.reset_success'));

                 
            } catch (EntityException $e) {
                 return redirect()->to(url()->previous())
                ->withError(lang()->get('responses/forgetpass.unknown_error'));
            }  

        }
      
        return view('admin.users.changePassword');
    }

}