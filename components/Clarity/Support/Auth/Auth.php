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
namespace Components\Clarity\Support\Auth;

use InvalidArgumentException;
use Components\Exceptions\EntityException;


class Auth
{
    private $request;
    private $session;
    private $response;
    private $security;

    public function __construct()
    {
        $this->request = di()->get('request');
        $this->session = di()->get('session');
        $this->response = di()->get('response');
        $this->security = di()->get('security');
    }

    /**
     * Attempt to login using the provided records and the password field.
     *
     * @param  array $records
     * @return bool
     */
    public function attempt($records)
    {
        $password_field = config()->app->auth->password_field;

        if (isset($records[$password_field]) === false) {
            throw new InvalidArgumentException('Invalid argument for password field.');
        }

        # get the password information

        $password = $records[$password_field];
        unset($records[$password_field]);

        # build the conditions

        $first = true;
        $conditions = null;

        foreach ($records as $key => $record) {
            if (! $first) {
                $conditions .= 'AND';
            }

            $conditions .= " {$key} = :{$key}: ";

            $first = false;
        }

        # find the informations provided in the $records

        $auth_model = config()->app->auth->model;

        $records = $auth_model::find([
            $conditions,
            'bind' => $records,
        ])->getFirst();

        # check if there is no record, then return false

        if (! $records) {
            return false;
        }

        // Check if the user was flagged
        if ( $records->status != 1 ) {
            flash()->session()->warning(
                'User is not active' 
            );
            return false;
        }

        # now check if the password given is matched with the
        # existing password recorded.
         
        if ($this->security->checkHash($password, $records->{$password_field})) {
            $this->session->set('isAuthenticated', true);
            $records->$password_field = null ;
            $records->token = null ;
            $this->session->set('user', $records);

            return true;
        }

        return false;
    }

    /**
     * Redirect based on the key provided in the url.
     *
     * @return mixed|bool
     */
    public function redirectIntended()
    {
        $redirect_key = config()->app->auth->redirect_key;

        $redirect_to = $this->request->get($redirect_key);

        if ($redirect_to) {
            return $this->response->redirect($redirect_to);
        }

        return false;
    }

    /**
     * To determine if the user is logged in.
     *
     * @return bool
     */
    public function check()
    {
        if ($this->session->has('isAuthenticated')) {
            return true;
        }

        return false;
    }

    /**
     * Get the stored user information.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->session->get('user');
    }

    /**
     * Destroy the current auth.
     *
     * @return bool
     */
    public function destroy()
    {
        $this->session->remove('isAuthenticated');
        $this->session->remove('user');

        return true;
    }


    #customs
    public function isAuthorizedVisitor()
    {
        return $this->check();
    }
    
    public function getUserId()
    {
        if (!$this->isAuthorizedVisitor()) {
            return null;
        }

        return (int)$this->user()->id;
 
    }
}
