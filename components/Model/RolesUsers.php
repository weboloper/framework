<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Users;
use Components\Model\Roles;

class RolesUsers extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'roles_users';
    }

    public function initialize()
    {
        $this->belongsTo('user_id', Users::class, 'id', ['alias' => 'user', 'reusable' => true]);
        $this->belongsTo('role_id', Roles::class, 'id', ['alias' => 'role', 'reusable' => true]);
    }

    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function setRole_id($role_id)
    {
        $this->role_id = $role_id;
        return $this;
    }

}