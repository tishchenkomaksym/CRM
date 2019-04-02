<?php


namespace App\Service\User\DepartmentManager;


use App\Constants\UserRoles;
use App\Entity\User;

class DepartmentManagerService
{
    public function addDepartmentManagerRole(User $user): User
    {
        $roles = $user->getRoles();
        if (!in_array(UserRoles::ROLE_DEPARTMENT_MANAGER, $roles, true)) {
            $roles[] = UserRoles::ROLE_DEPARTMENT_MANAGER;
            $user->setRoles($roles);
        }
        return $user;
    }

}