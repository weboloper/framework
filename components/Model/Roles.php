<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Access;
use Components\Model\RolesUsers;

class Roles extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'roles';
    }


    public function getList()
    {
        $entities = Roles::find(['columns' => ['id', 'name', 'description']]);
        $result = $entities->toArray();

        if (empty($result)) {
            $entity = $this->getOrCreateFirstDefaultRole();

            $result[0] = [
                'id'          => $entity->getId(),
                'name'        => $entity->getName(),
                'description' => $entity->getDescription(),
            ];
        }

        $result = array_map(function ($role) {
            $role['id'] = (int) $role['id'];

            return (object) $role;
        }, $result);

        return $result;
    }

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            RolesUsers::class,
            'role_id',
            'user_id',
            Users::class,
            'id',
            ['alias' => 'users']
        );
        $this->hasOne('id', Access::class, 'role_id', ['alias' => 'access', 'reusable' => true]);
    }


}