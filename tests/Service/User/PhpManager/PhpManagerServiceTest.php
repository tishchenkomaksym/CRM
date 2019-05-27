<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 07.03.2019
 * Time: 14:17
 */

namespace App\Service\User\PhpManager;

use App\Constants\UserRoles;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PhpManagerServiceTest extends TestCase
{

    public function testResetPhpManagerRoles()
    {
        $service = new PhpManagerService();
        $user = new User();
        $user->setRoles(['qwe']);
        $result = $service->addManagerRole($user);
        $assertUser = new User();
        $assertUser->setRoles(['qwe', UserRoles::ROLE_USER, UserRoles::ROLE_PHP_MANAGER]);
        $this->assertEquals($assertUser, $result);
    }
}
