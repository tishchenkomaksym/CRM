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
use PHPUnit\Framework\TestCase;

class ReportWorkHoursBuilderDecoratorTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testBuild()
    {
        $mockBuilder = $this->createMock(BaseWorkHoursInformationBuilder::class);
        $obj = new WorkHoursInformation();
        $mockBuilder->method('build')->willReturn($obj);
        $mockSalaryReport = $this->createMock(SalaryReportInfoRepository::class);
        $salaryInfo = new SalaryReportInfo();
        $salaryInfo->setCreateDate(new \DateTimeImmutable());
        $mockSalaryReport->method('findOneBy')->willReturn($salaryInfo);
        $workHoursBuilder = New ReportWorkHoursBuilderDecorator($mockBuilder, $mockSalaryReport);
        $user = new User();
        $result = $workHoursBuilder->build($user);
        $this->assertEquals($obj, $result);
    }
}
