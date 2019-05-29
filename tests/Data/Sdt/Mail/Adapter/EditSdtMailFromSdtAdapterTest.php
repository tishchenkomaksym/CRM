<?php

namespace App\Data\Sdt\Mail\Adapter;

use App\Data\Sdt\Mail\EditSdtMailData;
use App\Entity\Sdt;
use App\Entity\User;
use App\Repository\SDTEmailAssigneeRepository;
use App\Service\HolidayService;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EditSdtMailFromSdtAdapterTest extends TestCase
{

    /**
     * @var SDTEmailAssigneeRepository|MockObject
     */
    private $mock;
    /**
     * @var HolidayService|MockObject
     */
    private $holidayMock;

    public function setUp()
    {
        $this->mock=$this->createMock(SDTEmailAssigneeRepository::class);
        $this->holidayMock = $this->createMock(HolidayService::class);

    }

    /**
     * @throws NoDateException
     */
    public function testGetEditSdtMailException()
    {
        $object = new EditSdtMailFromSdtAdapter($this->mock);
        $sdt=new Sdt();
        $this->expectException(NoDateException::class);
        $object->getEditSdtMail($sdt,new DateTime(), 0, $this->holidayMock);
    }

    /**
     * @dataProvider dataProviderTestGetEditSdtMail
     * @param string $oldDateFrom
     * @param int $oldCount
     * @param string $oldDateTo
     * @param string $newDateFrom
     * @param string $newDateTo
     * @param $daysCount
     * @throws NoDateException
     * @throws \Exception
     */
    public function testGetEditSdtMail(
        string $oldDateFrom,
        int $oldCount,
        string $oldDateTo,
        string $newDateFrom,
        string $newDateTo,
        $daysCount
    ): void {


        $this->holidayMock->method('getHolidayBetweenDate')->willReturn([]);
        /** @var User|MockObject $userMock */
        $userMock = $this->createMock(User::class);
        $userMock->method('getId')->willReturn(1);
        $userMock->method('getName')->willReturn('name');
//        $emailAssigne=new SDTEmailAssignee();
        $this->mock->expects(self::once())->method('findBy')->willReturn([]);
        $object = new EditSdtMailFromSdtAdapter($this->mock);

        $sdt = new Sdt();
        $sdt->setCreateDate(new DateTime($newDateFrom));
        $sdt->setCount($daysCount);
        $sdt->setUser($userMock);
        $sdt->setActing('qwe');
        $sdt->setAtOwnExpense(false);

        $oldDate = new DateTime($oldDateFrom);
        $assertObject = new EditSdtMailData(
            'name',
            $oldDateFrom,
            $oldDateTo,
            $newDateFrom,
            $newDateTo,
            'qwe',
            $daysCount,
            false,
            []
        );
        $result = $object->getEditSdtMail($sdt, $oldDate, $oldCount, $this->holidayMock);
        $this->assertEquals($assertObject, $result);
    }

    public function dataProviderTestGetEditSdtMail()
    {
        return [
            [
                '2019-05-28',
                4,
                '2019-05-31',
                '2019-05-27',
                '2019-05-29',
                3,
            ],
        ];
    }
}
