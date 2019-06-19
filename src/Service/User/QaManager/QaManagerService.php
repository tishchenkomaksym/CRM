<?php


namespace App\Service\User\QaManager;

use App\Constants\UserRoles;
use App\Entity\User;

class QaManagerService
{
    public function addManagerRole(User $user): User
    {
        $roles = $user->getRoles();
        if (!in_array(UserRoles::ROLE_QA_MANGER, $roles, true)) {
            $roles[] = UserRoles::ROLE_QA_MANGER;
            $user->setRoles($roles);
        }
        return $user;
    }
}
