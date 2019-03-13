<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 13.03.2019
 * Time: 12:21
 */

namespace App\Service\User\PhpDeveloper\Hours;

use PHPUnit\Framework\TestCase;

class WorkHoursInformationTest extends TestCase
{
    /**
     * @var WorkHoursInformation
     */
    private $object;

    public function setUp()
    {
        $this->object = new WorkHoursInformation();
    }

    public function testGetLoggedTime()
    {
        $this->object->setLoggedTime(1.01);
        $this->assertEquals(1.01, $this->object->getLoggedTime());
    }

    public function testSetRequiredTime()
    {
        $this->object->setRequiredTime(1.01);
        $this->assertEquals(1.01, $this->object->getRequiredTime());
    }

    public function testSetLoggedTime()
    {
        $this->object->setLoggedTime(1.01);
        $this->assertEquals(1.01, $this->object->getLoggedTime());
    }

    public function testGetRequiredTime()
    {
        $this->object->setRequiredTime(1.01);
        $this->assertEquals(1.01, $this->object->getRequiredTime());
    }
}
