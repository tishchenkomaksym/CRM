<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
//        $user = new User();
//        $user->setEmail('ivan.melnichuk@onyx.com');
//        $user->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST']);
//        $user->setPassword(
//            $this->passwordEncoder->encodePassword(
//                $user,
//                'qwerty'
//            )
//        );
//        $manager->persist($user);

        $user = new User();
        $user->setEmail('recrutier@onyx.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'qwerty'
            )
        );
        $user->setRoles(['ROLE_USER', 'ROLE_RECRUITER']);
        $manager->persist($user);
        $manager->flush();
        $manager->persist($user);

        $user = new User();
        $user->setEmail('accountmanager@onyx.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'accountmanager@onyx.com'
            )
        );
        $user->setRoles(['ROLE_USER', 'ROLE_ACCOUNT_MANAGER']);
        $manager->persist($user);
        $manager->flush();
        $manager->persist($user);

        $user = new User();
        $user->setEmail('hr@onyx.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'qwerty'
            )
        );
        $user->setRoles(['ROLE_USER', 'ROLE_HR', 'ROLE_MANAGE_HOLIDAYS', 'ROLE_MANAGE_MONTHLY_SDT']);
        $manager->persist($user);
        $manager->flush();
    }
}
