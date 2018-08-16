<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Users;

class Role extends \Components\Model\Services\Service
{
	const ADMIN_SYSTEM_ROLE = 'admin';
    const USER_SYSTEM_ROLE = 'user';
    const GUEST_SYSTEM_ROLE = 'guest';
    const MODERATOR_SYSTEM_ROLE = 'moderator';


	 /**
     * Checks whether the User is Admin.
     *
     * @return bool
     */
    // public function isAdmin()
    // {
    //     return in_array(Role::ADMINS_SYSTEM_ROLE, $this->getRoleNamesForCurrentViewer());
    // }
    /**
     * Checks whether the User is moderator.
     *
     * @return bool
     */
    // public function isModerator()
    // {
    //     return in_array(Role::MODERATORS_SYSTEM_ROLE, $this->getRoleNamesForCurrentViewer());
    // }
}
