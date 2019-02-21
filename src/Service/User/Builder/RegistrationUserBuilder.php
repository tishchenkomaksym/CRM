<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 21.02.2019
 * Time: 10:03
 */

namespace App\Service\User\Builder;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationUserBuilder
{
    public static function build(User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST']);
        return $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
    }
}
