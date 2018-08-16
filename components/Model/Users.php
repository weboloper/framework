<?php

namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

use Components\Model\Access;
use Components\Model\Roles;
use Components\Model\Posts;
use Components\Model\UserMeta;


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

    const STATUS_DELETED  = 0;
    const STATUS_DEFAULT  = 1;
 
    const GENDER_UNKNOWN = 9;
    const GENDER_MALE    = 1;
    const GENDER_FEMALE  = 2;


    const USER_STATUS = [

        self::STATUS_DEFAULT   => "DEFAULT",
        self::STATUS_DELETED   => "DELETED",
          
    ];

    // This is column map and default post type
    const COLUMN_MAP = [ 
        "id",
        "email",
        "password",
        "username",
        "name",
        "token",
        "activated",
        "forgetpass",
        "status",
        "created_at",
        "updated_at",
        "deleted_at",
        "lastPasswdReset" 
    ];

    const USER_METAS = [ 'test'  => 'Test'  ]; 

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

        // $this->keepSnapshots(true);
        
        $this->addBehavior(
            new SoftDelete(
                [
                    "field" => "status",
                    "value" =>  "0",
                ]
            )
        );

         $this->hasManyToMany(
            'id',
            RolesUsers::class,
            'user_id',
            'role_id',
            Roles::class,
            'id',
            ['alias' => 'roles']
        );
        $this->hasMany('id', UserMeta::class, 'user_id', ['alias' => 'meta', 'reusable' => true]);
        $this->hasMany('id', RolesUsers::class, 'user_id', ['alias' => 'rolesUsers', 'reusable' => true]);
        $this->hasMany('id', Posts::class, 'user_id', ['alias' => 'posts', 'reusable' => true]);
    }


    public function getUserStatus()
    {
        return self::USER_STATUS;
    }

 
    
    
    public function getId()
    {
      return $this->id;
    }

    public function setId($id)
    {
      $this->id = $id;
      return $this ;
    }

    public function getEmail()
    {
      return $this->email;
    }

    public function setEmail($email)
    {
      $this->email = $email;
      return $this ;
    }

    public function getPassword()
    {
      return $this->password;
    }

    public function setPassword($password)
    {
      $this->password = $password;
      return $this ;
    }

    public function getUsername()
    {
      return $this->username;
    }

    public function setUsername($username)
    {
      $this->username = $username;
      return $this ;
    }

    public function getName()
    {
      return $this->name;
    }

    public function setName($name)
    {
      $this->name = $name;
      return $this ;
    }

    public function getToken()
    {
      return $this->token;
    }

    public function setToken($token)
    {
      $this->token = $token;
      return $this ;
    }

    public function getActivated()
    {
      return $this->activated;
    }

    public function setActivated($activated)
    {
      $this->activated = $activated;
      return $this ;
    }

    public function getForgetpass()
    {
      return $this->forgetpass;
    }

    public function setForgetpass($forgetpass)
    {
      $this->forgetpass = $forgetpass;
      return $this ;
    }

    public function getStatus()
    {
      return $this->status;
    }

    public function setStatus($status)
    {
      $this->status = $status;
      return $this ;
    }

    public function getCreatedAt()
    {
      return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
      $this->created_at = $created_at;
      return $this ;
    }

    public function getUpdatedAt()
    {
      return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
      $this->updated_at = $updated_at;
      return $this ;
    }

    public function getDeletedAt()
    {
      return $this->deleted_at;
    }

    public function setDeletedAt($deleted_at)
    {
      $this->deleted_at = $deleted_at;
      return $this ;
    }

    public function getLastPasswdReset()
    {
      return $this->lastPasswdReset;
    }

    public function setLastPasswdReset($lastPasswdReset)
    {
      $this->lastPasswdReset = $lastPasswdReset;
      return $this ;
    }


     


}
