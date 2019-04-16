<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 16:33
 */

namespace App\Service;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserInformationServiceTest extends TestCase
{

    public function testGetSystemName()
    {
        $user=new User();
        $user->setEmail('ivan.melnichuk@onyx.com');
        $this->assertEquals('ivan.melnichuk',UserInformationService::getSystemName($user));
    }
}
