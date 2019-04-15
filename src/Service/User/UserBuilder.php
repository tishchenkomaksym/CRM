<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/22/2019
 * Time: 11:07 AM
 */

namespace App\Service\User;

use App\Entity\User;

class UserBuilder
{
    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public static function build(User $user)
    {
        $user->setCreateDate(new \DateTime());
        return $user;
    }
}
