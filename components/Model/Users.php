<?php

namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Access;
use Components\Model\Roles;
use Components\Model\Posts;

class Users extends Model
{
    use Timestampable;
    use SoftDeletable;

    public $email;
    public $password;
    public $token;
    protected $name;
    protected $activated;

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
    public function setActivated($bool)
    {
        $this->activated = (int) $bool;

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
     * To know if the account is activated.
     *
     * @return bool
     */
    public function getActivated()
    {
        return (bool) $this->activated;
    }

     public function initialize()
     {
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
