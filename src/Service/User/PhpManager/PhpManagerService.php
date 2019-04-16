<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 13.02.2019
 * Time: 12:12
 */

namespace App\Service\User\PhpManager;

use App\Constants\UserRoles;
use App\Entity\User;

class PhpManagerService
{
    public function addManagerRole(User $user): User
    {
        $roles = $user->getRoles();
        if (!in_array(UserRoles::ROLE_PHP_MANAGER, $roles, true)) {
            $roles[] = UserRoles::ROLE_PHP_MANAGER;
            $user->setRoles($roles);
        }
        return $user;
    }
}
