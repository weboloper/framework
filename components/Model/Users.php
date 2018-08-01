<?php

namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Access;
use Components\Model\Roles;
use Components\Model\Posts;


use Phalcon\Mvc\Model\Behavior\Blameable;
use Components\Model\Audit;

class Users extends Model
{
    use Timestampable;
    use SoftDeletable;

    public $id;
    public $email;
    public $password;
    public $token;
    protected $name;
    public $username;
    protected $lastPasswdReset;
    public $status;

    const STATUS_DEFAULT  = 0;
 
     

    const GENDER_UNKNOWN = 9;
    const GENDER_MALE    = 1;
    const GENDER_FEMALE  = 2;


    const USER_STATUS = [

        self::STATUS_DEFAULT   => "DEFAULT",
          
    ];

    public function getUserStatus()
    {
        return self::USER_STATUS;
    }

 
    /**
     * By every request, phalcon will always pull this function
     * as basis to know what is the table's name.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set the name field.
     *
     * @param string $name setting the name of the user
     * @return mixed
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the username.
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the user's username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Set the email.
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the user's email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the column activated in the table as boolean.
     *
     * @param bool $bool a boolean value to be based if activated or not
     * @return mixed
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;

        return $this;
    }

    /**
     * Set the column activated in the table as boolean.
     *
     * @param bool $bool a boolean value to be based if activated or not
     * @return mixed
     */
    public function setForgetpass($bool)
    {
        $this->forgetpass = (int) $bool;

        return $this;
    }

    /**
     * To know if the account is status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    public function setLastPasswdReset($lastPasswdReset)
    {
        $this->lastPasswdReset = $lastPasswdReset;
        return $this;
    }

    public function getLastPasswdReset()
    {
        return $this->lastPasswdReset;
    }

     public function initialize()
     {  
        // if (auth()->isAuthorizedVisitor()) {
           // $this->addBehavior(
           //      new Blameable(
           //          [
           //              'auditClass'       => Audit::class,
           //          ]
           //      )
           //  );
        // }

        $this->keepSnapshots(true);
        

         $this->hasManyToMany(
            'id',
            RolesUsers::class,
            'user_id',
            'role_id',
            Roles::class,
            'id',
            ['alias' => 'roles']
        );
        $this->hasMany('id', RolesUsers::class, 'user_id', ['alias' => 'rolesUsers', 'reusable' => true]);
        $this->hasMany('id', Posts::class, 'user_id', ['alias' => 'posts', 'reusable' => true]);
    }


}
