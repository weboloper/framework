<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Users;
use Components\Model\RolesUsers;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;


class User extends \Components\Model\Services\Service
{
	
     /**
     * @var Users
     */
    protected $viewer;

    /**
     * Gets role names for current viewer.
     *
     * @return string[]
     */
    public function getRoleNamesForCurrentViewer()
    {
        $entity = $this->getCurrentViewer();
        if ($entity->getId() == 0 || $entity->countRoles() == 0) {
            return [Role::GUEST_SYSTEM_ROLE];
        }
        return array_column($entity->getRoles(['columns' => ['name']])->toArray(), 'name');
    }

    /**
     * Gets current viewer.
     *
     * @return Users
     */
    public function getCurrentViewer()
    {
        if ($this->viewer) {
            return $this->viewer;
        }
         
        $entity = null;
        // if ($this->auth->isAuthorizedVisitor()) { // her ikisi de uygun
        if (auth()->isAuthorizedVisitor()) {
            $entity = $this->findFirstById($this->auth->getUserId());
        }
        if (!$entity) {
            $entity = $this->createDefaultViewer();
        }
        $this->viewer = $entity;
        return $entity;
    }

    /**
     * Sets current viewer.
     *
     * @param Users $entity
     */
    public function setCurrentViewer(Users $entity)
    {
        $this->viewer = $entity;
    }

    protected function createDefaultViewer()
    {
        $entity = new Users(['id' => 0 ]);
        return $entity;
    }


	 /**
     * Checks whether the User is Admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return in_array(Role::ADMIN_SYSTEM_ROLE, $this->getRoleNamesForCurrentViewer());
    }
    /**
     * Checks whether the User is moderator.
     *
     * @return bool
     */
    public function isModerator()
    {
        return in_array(Role::MODERATOR_SYSTEM_ROLE, $this->getRoleNamesForCurrentViewer());
    }




    /**
     * Finds User by ID.
     *
     * @param  int $id The User ID.
     * @return Users|null
     */
    public function findFirstById($id)
    {
        return Users::findFirstById($id) ?: null;
    }
    /**
     * Get User by ID.
     *
     * @param  int $id The User ID.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstById($id)
    {
        if (!$user = $this->findFirstById($id)) {
            throw new EntityNotFoundException($id);
        }
        return $user;
    }

    /**
     * Finds User by ID.
     *
     * @param  int $id The User ID.
     * @return Users|null
     */
    public function findFirstByEmail($email)
    { 
        $user = Users::query()
            ->where('email = :email:', ['email' => $email])
            ->limit(1)
            ->execute();
        return $user->valid() ? $user->getFirst() : null; 
    }  

    public function getFirstByEmail($email)
    {
        if (!$user = $this->findFirstByEmail($email)) {
            throw new EntityNotFoundException($email);
        }
        return $user;
    }


    public function validateResetPasswordInterval(Users $entity)
    {   

        $timezone = config()->app->timezone;
        $passwdResetInterval = abs( 111 );

        $lastResetDate = $entity->lastPasswdReset;
        if (!empty($lastResetDate) && $passwdResetInterval) {
            $nextDateForReset = new DateTime(
                date('Y-m-d H:i:s', $lastResetDate + $passwdResetInterval),
                new DateTimeZone($timezone)
            );
            $now = new DateTime('now', new DateTimeZone($timezone));
            if ($nextDateForReset > $now) {
                $nextReset = $nextDateForReset->format('Y-m-d H:i:s') . ' ' . $timezone;
                throw new EntityException(
                    $entity,
                    $nextReset 
                );
            }
        }
    }


    public function resetPassword(Users $entity, $token )
    {   
        
        $this->validateResetPasswordInterval($entity);
        $newAttributes = [
            'token' => $token,
            'forgetpass' => true, 
            'lastPasswdReset'  => time(),
        ];
        $entity->assign($newAttributes);
        if (!$entity->save()) {
            throw new EntityException(
                $entity,
                'We were unable to reset your password. Please try again later.'
            );
        }
 
    }

    public function assignNewPassword(Users $entity, $password)
    {   
        $token = bin2hex(random_bytes(100));

        $newAttributes = [
            'forgetpass' => false, 
            'password'  => security()->hash( $password ),
        ];
        $entity->assign($newAttributes);
        if (!$entity->save()) {
            throw new EntityException(
                $entity,
                'We were unable to change your password. Please try again later.'
            );
        }
    }

    public function saveRolesInUsers($roles_array, $object)
    {
        $rolesId = [];
        $user_id    = $object->getId();
         
        $userRoles = RolesUsers::find(
            [
                'user_id = ?0',
                'bind' => [$user_id]
            ]
        );

        foreach ($userRoles as $value) {
            $rolesId[] = $value->role_id;
        }

        //Deleted tags
        $rows2 = array_diff( $rolesId , $roles_array  );
        $this->deletedRole($user_id, $rows2);

        $rows = array_diff( $roles_array, $rolesId  );
        foreach ($rows as $role_id) {
            $userRole = new RolesUsers();
            $userRole->setUser_id($user_id);
            $userRole->setRole_id($role_id);

            if (!$userRole->save()) {
                return false;
            }
            //Update the total of posts related to a tags
            // if ($object->getOperationMade() == \Phalcon\Mvc\Model::OP_CREATE) {
            //     $tags    = Tags::findFirstById($tagId);
            //     $number  = $tags->getNumberposts();
            //     $tags->setNumberPosts($number + 1);
            //     $tags->save();
            // }
        }

        return true;
    }

    public function deletedRole($userId, $roleIds)
    {
        foreach ($roleIds as $id) {
            $object = RolesUsers::find(
                [
                    'user_id = ?0 AND role_id =?1',
                    'bind' => [$userId, $id]
                ]
            );

            if ($object) {
                $object->delete();
            }
        }
    }


}
