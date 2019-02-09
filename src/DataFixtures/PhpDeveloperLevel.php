<?php

namespace App\DataFixtures;

use App\Entity\PhpDeveloperLevelTest;
use App\Entity\PhpDeveloperLevelTestPassed;
use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\User;
use App\Entity\UserPhpDeveloperLevelRelation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PhpDeveloperLevel extends Fixture
{
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $phpDeveloperLevel = new \App\Entity\PhpDeveloperLevel();
        $phpDeveloperLevel->setTitle('PHP Junior Level 1');
        $manager->persist($phpDeveloperLevel);
        $this->juniorUser($manager, $phpDeveloperLevel);
        $this->juniorUserWithoutManager($manager, $phpDeveloperLevel);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Junior Level 2');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Junior Level 3');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Middle Level 1');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Middle Level 2');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Middle Level 3');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Senior Level 1');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Senior Level 2');
        $manager->persist($product);
        $product = new \App\Entity\PhpDeveloperLevel();
        $product->setTitle('PHP Senior Level 3');
        $manager->persist($product);
        $manager->flush();

    }

    private function juniorLvlOneTests(
        ObjectManager $manager,
        \App\Entity\PhpDeveloperLevel $phpDeveloperLevel,
        User $user,
        User $secondJuniour
    )
    {
        $test = new PhpDeveloperLevelTest();
        $test->setTitle('MySQL Level 1');
        $test->setLink('https://drive.google.com/open?id=17rYa4kxiGQzsmBh8zANz3B6PEQdBShVlHBGmctpKSEA');
        $test->setPhpDeveloperLevel($phpDeveloperLevel);
        $manager->persist($test);


        $test = new PhpDeveloperLevelTest();
        $test->setTitle('PHP Base');
        $test->setLink('https://docs.google.com/forms/d/1SoIxgZOp8FkzycPYXu2L_VgS-OrNLJ1a9-y3USDW2gk/');
        $test->setPhpDeveloperLevel($phpDeveloperLevel);
        $manager->persist($test);

        $test = new PhpDeveloperLevelTest();
        $test->setTitle('PHP OOP');
        $test->setLink('https://drive.google.com/open?id=1ROB5y3_EO8lFmMgA-zoxAG64ESMGQUBWPrsemxRq6Ik');
        $test->setPhpDeveloperLevel($phpDeveloperLevel);
        $manager->persist($test);

        $passed = new PhpDeveloperLevelTestPassed();
        $passed->setUser($secondJuniour);
        $passed->setPhpDeveloperLevelTest($test);
        $manager->persist($passed);
    }

    /**
     * @param ObjectManager $manager
     * @param \App\Entity\PhpDeveloperLevel $phpDeveloperLevel
     * @throws \Exception
     */
    private function juniorUser(ObjectManager $manager, \App\Entity\PhpDeveloperLevel $phpDeveloperLevel)
    {
        $user = new User();
        $user->setEmail('junior1@onyx.com');
        $user->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST', 'ROLE_PHP_DEVELOPER']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'junior1@onyx.com'
            )
        );
        $manager->persist($user);
        $this->juniorLvlOneTests($manager, $phpDeveloperLevel, $user);

        $relation = new UserPhpDeveloperLevelRelation();
        $relation->setUser($user);
        $dateTime = new \DateTimeImmutable();
        $relation->setCreateDate($dateTime);
        $relation->setPhpDeveloperLevel($phpDeveloperLevel);
        $manager->persist($relation);


        $managerUser = new User();
        $managerUser->setEmail('juniorPM@onyx.com');
        $managerUser->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST', 'ROLE_PHP_MANAGER']);
        $managerUser->setPassword(
            $this->passwordEncoder->encodePassword(
                $managerUser,
                'juniorPM@onyx.com'
            )
        );
        $manager->persist($managerUser);

        $relation = new PhpDeveloperManagerRelation();
        $relation->setManager($managerUser);
        $relation->setPhpDeveloper($user);
        $manager->persist($relation);
    }

    /**
     * @param ObjectManager $manager
     * @param \App\Entity\PhpDeveloperLevel $phpDeveloperLevel
     * @throws \Exception
     */
    private function juniorUserWithoutManager(ObjectManager $manager, \App\Entity\PhpDeveloperLevel $phpDeveloperLevel)
    {
        $user = new User();
        $user->setEmail('junior2@onyx.com');
        $user->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST', 'ROLE_PHP_DEVELOPER']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'junior2@onyx.com'
            )
        );
        $manager->persist($user);

        $relation = new UserPhpDeveloperLevelRelation();
        $relation->setUser($user);
        $dateTime = new \DateTimeImmutable();
        $relation->setCreateDate($dateTime);
        $relation->setPhpDeveloperLevel($phpDeveloperLevel);
        $manager->persist($relation);


        $managerUser = new User();
        $managerUser->setEmail('juniorPM@onyx.com');
        $managerUser->setRoles(['ROLE_USER', 'ROLE_SDT_REQUEST', 'ROLE_PHP_MANAGER']);
        $managerUser->setPassword(
            $this->passwordEncoder->encodePassword(
                $managerUser,
                'juniorPM@onyx.com'
            )
        );
        $manager->persist($managerUser);

        $relation = new PhpDeveloperManagerRelation();
        $relation->setManager($managerUser);
        $relation->setPhpDeveloper($user);
        $manager->persist($relation);
    }

}
