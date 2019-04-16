<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 13.02.2019
 * Time: 12:12
 */

namespace App\Service\User\PhpDeveloperLevel;

use App\Entity\User;

class PhpDeveloperLevelService
{
    public static function resetPhpDeveloperRoles(User $user)
    {
        $user->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST', 'ROLE_PHP_DEVELOPER']);
        return $user;
    }
}
