<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 13.03.2019
 * Time: 15:41
 */

namespace App\Service\User\PhpDeveloper\Hours;

use App\Entity\SalaryReportInfo;
use App\Entity\User;
use App\Repository\SalaryReportInfoRepository;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReportWorkHoursBuilderDecoratorTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testBuild()
    {
        /** @var BaseWorkHoursInformationBuilder|MockObject $mockBuilder */
        $mockBuilder = $this->createMock(BaseWorkHoursInformationBuilder::class);
        $obj = new WorkHoursInformation();
        $mockBuilder->method('build')->willReturn($obj);
        /** @var SalaryReportInfoRepository|MockObject $mockSalaryReport */
        $mockSalaryReport = $this->createMock(SalaryReportInfoRepository::class);
        $salaryInfo = new SalaryReportInfo();
        $salaryInfo->setCreateDate(new DateTimeImmutable());
        $mockSalaryReport->method('getTodaySalaryReport')->willReturn($salaryInfo);
        $workHoursBuilder = New ReportWorkHoursBuilderDecorator($mockBuilder, $mockSalaryReport);
        $user = new User();
        $obj->setUser($user);
        $result = $workHoursBuilder->build($user, new DateTime());
        $this->assertEquals($obj, $result);
    }
}
