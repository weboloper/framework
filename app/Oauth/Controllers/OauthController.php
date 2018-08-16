<?php

namespace App\Oauth\Controllers;

use Exception;
use Components\Model\Users;
use Components\Validation\LoginValidator;
use Components\Validation\RegistrationValidator;
use Components\Validation\ForgetpassValidator;
use Components\Validation\ResetpassValidator;
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;
use Components\Library\Facebook\Auth as FacebookAuth;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;

use Components\Model\Services\Service\User as userService;
use Components\Model\Services\Service\FailedLogin as failedLogin;
class OauthController extends Controller
{   
    protected $userService;
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {   
        parent::initialize();
        
        $this->middleware('csrf', [
            'only' => [
                'attemptToLogin',
            ],
        ]);

        $this->middleware('auth', [
            'only' => [
                'isLogged',
                'showChangePassword'
            ],
        ]);

        $this->userService = new userService;
        $this->failedLoginService = new failedLogin;

    }
 

    /**
     * GET | This shows the form to register.
     *
     * @return mixed
     */
    public function showRegistrationForm()
    {   
        if(auth()->check())
        {   
            // auth()->destroy();
            return redirect()->to(url()->to('/'));
        }
        # find session if it has an 'input'
        if (session()->has('input')) {

            # get the session 'input' then remove it
            $input = session()->get('input');
            session()->remove('input');

            # set the tag 'email' to rollback the value inputted
            tag()->setDefault('email', $input['email']);
        }

        return view('auth.showRegistrationForm');
    }

    /**
     * POST | This handles the registration with validation.
     *
     * @return mixed
     */
    public function storeRegistrationForm()
    {   
        if(auth()->check())
        {   
            auth()->destroy();
            return redirect()->to(url()->to('/'));
        }

        $inputs = request()->get();

        // this is if username is not allowed
        if(!$this->config->app->auth->usernames){
            $inputs['username'] = $inputs['email'];
        }

        $validator = new RegistrationValidator;
        $validation = $validator->validate($inputs);

        if (count($validation)) {
            session()->set('input', $inputs);

            return redirect()->to(url()->previous())
                ->withError(RegistrationValidator::toHtml($validation));
        }

        $token = bin2hex(random_bytes(100));

        // $connection = db()->connection();
        $connection = db();

        try {
            $connection->begin();

            $user = new Users;

            $success = $user->create([
                'email'    => $inputs['email'],
                'username' => $inputs['username'],
                'name'     => $inputs['name'],
                'password' => security()->hash($inputs['password']),
                'token'    => $token,
             ]);

            if ($success === false) {
                throw new Exception(
                    'It seems we can\'t create an account, '.
                    'please check your access credentials!'
                );
            }

            queue(
                // 'Components\Queue\Email@registeredSender',
                \Components\Queue\Email::class,
                [
                    'function' => 'registeredSender',
                    'template' => 'emails.registered-inlined',
                    'to' => $inputs['email'],
                    'url' => route('activateUser', ['token' => $token]),
                    'subject' => 'You are now registered, activation is required.',
                ]
            );

            $connection->commit();

        } catch (TransactionFailed $e) {
            $connection->rollback();
            throw $e;
        } catch (Exception $e) {
            $connection->rollback();
            throw $e;
        }

        return redirect()->to(route('showLoginForm'))
            ->withSuccess(lang()->get('responses/register.creation_success'));
    }

    /**
     * GET | This shows the login form.
     *
     * @return mixed
     */
    public function showLoginForm()
    {      
        return view('auth.showLoginForm');
    }

    /**
     * POST | This handles the loging.
     *
     * @return mixed
     */
    public function attemptToLogin()
    {   
        if(auth()->check())
        {   
            auth()->destroy();
            return redirect()->to(url()->to('/'));
        }

        $inputs = request()->get();

        $validator = new LoginValidator;
        $validation = $validator->validate($inputs);

        if (count($validation)) {
            session()->set('input', $inputs);

            return redirect()->to(url()->previous())
                ->withError(LoginValidator::toHtml($validation));
        }

        $credentials = [
            'email' => $inputs['email'],
            'password' => $inputs['password'],
            // 'activated' => 1,
            // 'status' => 1,
        ];

        if (auth()->attempt($credentials)) {
            if ($redirect = auth()->redirectIntended()) {
                
                return $redirect;
            }

            return redirect()->to(url()->to( $this->config->app->auth->login_redirect ));
        }

        $user = $this->userService->getFirstByEmail($credentials['email']);

        $userData = [
            'user_id'   =>  $user->getId(),
            'userAgent' =>  request()->getUserAgent(),
            'ipaddress' =>  request()->getClientAddress(true)  
        ];

       try {
            $this->failedLoginService->create($userData ); 
        } catch (EntityException $e) {

        }

        return redirect()->to(url()->previous())
            ->withError(lang()->get('responses/login.no_user'));
    }

    /**
     * GET, POST | This logouts the current session logged-in.
     *
     * @return mixed
     */
    public function logout()
    {
        auth()->destroy();

        return redirect()->to(route('showLoginForm'));
    }

    /**
     * GET | This activates a user record to be able to login.
     *
     * @return mixed
     */
    public function activateUser($token)
    {
        $user = Users::find([
            'token = :token: AND activated = :activated:',
            'bind' => [
                'token' => $token,
                'activated' => 0,
            ],
        ])->getFirst();

        if (! $user) {
            flash()->session()->warning(
                'We cant find your request, please '.
                'try again, or contact us.'
            );

            return view('errors.404');
        }

        $user->setActivated(true);

        if ($user->save() === false) {
            foreach ($user->getMessages() as $message) {
                flash()->session()->error($message);
            }
        } else {
            flash()->session()->success(
                'You have successfully activated your account, '.
                'you are now allowed to login.'
            );
        }

        return redirect()->to(route('showLoginForm'));
    }


    /**
     * GET, POST | This logouts the current session logged-in.
     *
     * @return mixed
     */
    public function isLogged()
    {
        return view('auth.isLogged');
    }
    
    /**
     * GET, POST | This logouts the current session logged-in.
     *
     * @return mixed
     */
    public function index()
    {
        return view('pages.welcome');
    }


    /**
     * GET | This shows the form to register.
     *
     * @return mixed
     */
    public function showForgetPasswordForm()
    {   
         
        if(request()->isPost()) {
            
            $inputs = request()->get();

            $validator = new ForgetpassValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(ForgetpassValidator::toHtml($validation));
            }

            try {
                $token = bin2hex(random_bytes(100));

                $user = $this->userService->getFirstByEmail( $inputs['email']);
                $params = $this->userService->resetPassword($user, $token);

                queue(
                    // 'Components\Queue\Email@registeredSender',
                    \Components\Queue\Email::class,
                    [
                        'function' => 'registeredSender',
                        'template' => 'emails.forgetpass',
                        'to' => $inputs['email'],
                        'url' => route('showResetPasswordForm', ['token' => $token , 'id'  => $user->getId() ]),
                        'subject' => 'You tried to reset your password.',
                    ]
                );
                 
            } catch (EntityException $e) {
                 return redirect()->to(url()->previous())
                ->withError(lang()->get('responses/forgetpass.wait_more', ['time' => $e->getMessage() ]));

                 
            } catch (EntityNotFoundException $e) {
                return redirect()->to(url()->previous())
                ->withError(lang()->get('responses/forgetpass.no_email'));
            }

            return redirect()->to(route('showLoginForm'))
            ->withSuccess(lang()->get('responses/forgetpass.creation_success'));

        }
        
        # find session if it has an 'input'
        if (session()->has('input')) {

            # get the session 'input' then remove it
            $input = session()->get('input');
            session()->remove('input');

            # set the tag 'email' to rollback the value inputted
            tag()->setDefault('email', $input['email']);
        }

        return view('auth.showForgetPasswordForm');
    }


    /**
     * GET | This shows the form to register.
     *
     * @return mixed
     */
    public function showResetPasswordForm($token, $id)
    {   
    
        $user = Users::find([
            'token = :token: AND forgetpass = :forgetpass: AND id = :id: ',
            'bind' => [
                'token' => $token,
                'forgetpass' => true,
                'id' => $id
            ],
        ])->getFirst();

        if (! $user) {
            flash()->session()->warning(
                'We cant find your request, please '.
                'try again, or contact us.'
            );

            return view('errors.404');
        }

        // $user->setToken('');
        // $user->setForgetpass(0);
        // $user->save();

        if(request()->isPost()) {
            
            $inputs = request()->get();

            $validator = new ResetpassValidator;
            $validation = $validator->validate($inputs);

            if (count($validation)) {
                session()->set('input', $inputs);

                return redirect()->to(url()->previous())
                    ->withError(ResetpassValidator::toHtml($validation));
            }

            try {
                $this->userService->assignNewPassword($user, $inputs['password']);
                return redirect()->to(route('showLoginForm'))
                ->withSuccess(lang()->get('responses/forgetpass.reset_success'));

                 
            } catch (EntityException $e) {
                 return redirect()->to(url()->previous())
                ->withError(lang()->get('responses/forgetpass.unknown_error'));
            }  

        }
 
 
        # find session if it has an 'input'
        if (session()->has('input')) {

            # get the session 'input' then remove it
            $input = session()->get('input');
            session()->remove('input');

            # set the tag 'email' to rollback the value inputted
            tag()->setDefault('email', $input['email']);
        }

        return view('auth.showResetPasswordForm');
    }


    public function showChangePassword()
    {   
    
        if (request()->isPost()) {

            $userId = auth()->getUserId() ;

            if (!$object = Users::findFirstById($userId) ) {
                return redirect()->to( url(""))->withError("Nesne bulunamadı");
            }
             
            $inputs = request()->get();

            if( $inputs['password'] != $inputs['repassword'])
            {
                return redirect()->to( url("oauth/change-password/"))->withError("Passwords dont match!");
            }

            $password_field = config()->app->auth->password_field;

            if (!$this->security->checkHash($inputs['current'], $object->{$password_field})) {
                return redirect()->to( url("oauth/change-password/"))->withError("Wrong password!");
            }

            try {
                $this->userService->assignNewPassword($object,$inputs['password'] );
               return redirect()->to( url("oauth/change-password/"))
                ->withSuccess("Password changed successfully");

                 
            } catch (EntityException $e) {
                 return redirect()->to(url()->previous())
                ->withError( $e);
            }  


        }

        return view('auth.showChangePassword');
    }

    
    /**
     * @return array|\Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function loginFacebook()
    {   

        // $this->middleware('auth');
        $this->view->disable();
        if (! $this->auth->check() ) {
            $auth = new FacebookAuth($this->config->app->facebook);
            return $auth->authorize();
        }
        $this->flashSession->success(t('Welcome back '. $this->auth->getName()));
        return $this->indexRedirect();
    }

     /**
     * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
     */
    public function tokenFacebook()
    {   

        $this->view->disable();
        $auth = new FacebookAuth($this->config->app->facebook);
        list($uid, $token, $user) = $auth->authorize();
        if (isset($token) && is_object($token)) {
            //Edit/Create the user
            $object = Users::findFirstByUuidFacebook($uid);
            $this->commonOauthSave($uid, $user, $token, $object, 'Facebook');
        } else {
            $this->flashSession->error('Invalid Google response. Please try again');
            return $this->response->redirect();
        }
    }




    public function test()
    {

        try {
        queue(
                // 'Components\Queue\Email@registeredSender',
                \Components\Queue\Email::class,
                [
                    'function' => 'registeredSender',
                    'template' => 'emails.registered-inlined',
                    'to' => "tesst@test.com",
                    'url' => route('activateUser', ['token' => "asd"]),
                    'subject' => 'You are now registered, activation is required.',
                ]
            );
        } catch (\Exception $e) {
            print $e;
        }

    }
}
