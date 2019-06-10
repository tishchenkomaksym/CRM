<?php

namespace App\Service\Sdt\Create\FormValidators;

use App\Data\Sdt\SdtCollection;
use App\Entity\Sdt;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;
use App\Service\Sdt\Interval\EndDateOfSdtCalculator;
use App\Service\UserInformationService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class SdtPeriodValidatorTest extends TestCase
{
    /**
     * @var ExecutionContextInterface|MockObject
     */
    private $contextMock;
    /**
     * @var ConstraintViolationBuilderInterface|MockObject
     */
    private $violationBuilderMock;
    public function setUp()
    {
        $this->contextMock = $this->createMock(ExecutionContextInterface::class);
        $this->violationBuilderMock = $this->createMock(ConstraintViolationBuilderInterface::class);
    }

    /**
     * @dataProvider dataProviderTestValidate
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param \DateTimeInterface $startPeriod
     * @param int $count
     * @param string $team
     * @throws \Exception
     */
    public function testValidate(\DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        \DateTimeInterface $startPeriod,
        int $count,
        string $team)
    {
        /** @var Sdt|MockObject $valueMock */
        $valueMock = $this->createMock(Sdt::class);
        $valueMock->method('getAtOwnExpense')->willReturn(false);
        /** @var User|MockObject $userMock */
        $userMock = $this->createMock(User::class);
        /** @var SdtPeriod|MockObject $constraintMock */
        $constraintMock = $this->createMock(SdtPeriod::class);
        /** @var HolidayService|MockObject $holidayService */
        $holidayService = $this->createMock(HolidayService::class);
        /** @var UserInfoRepository|MockObject $userInfoRepositoryMock */
        $userInfoRepositoryMock = $this->createMock(UserInfoRepository::class);
        /** @var EndDateOfSdtCalculator|MockObject $endDateCalculator */
        $endDateCalculator = $this->createMock(EndDateOfSdtCalculator::class);
        /** @var UserInformationService|MockObject $userInfoService */
        $userInfoService = $this->createMock(UserInformationService::class);
        /** @var UserInfo|MockObject $userInfo */
        $userInfo = $this->createMock(UserInfo::class);
        $userInfo->method('getUser')->willReturn($userMock);
        /** @var Sdt|MockObject $sdt */
        $sdt = $this->createMock(Sdt::class);
        /** @var SdtCollection|MockObject $sdtCollection */
        $sdtCollection = $this->createMock(SdtCollection::class);



        $sdt->method('getCreateDate')->willReturn($startPeriod);
        $sdt->method('getCount')->willReturn($count);
        $userInfoService->method('getAllUserSdt')->willReturn($sdtCollection);
        $sdtCollection->method('getItems')->willReturn([$sdt]);
        $userInfo->method('getSubTeam')->willReturn($team);

        $object = new SdtPeriodValidator($endDateCalculator, $userInfoRepositoryMock, $holidayService, $userInfoService);

        $object->initialize($this->contextMock);
        $constraintMock->method('getOldSdtEntity')->willReturn($valueMock);

        $valueMock->method('getCreateDate')->willReturn($startDate);
        $endDateCalculator->method('calculate')->willReturn($endDate);

        $this->violationBuilderMock->expects(self::once())->method('addViolation');
        $this->contextMock->expects(self::once())->method('buildViolation')->willReturn($this->violationBuilderMock);
        $object->validate($valueMock, $constraintMock);

    }

    public function dataProviderTestValidate()
    {
        return [
            [
                new \DateTime('2019-05-30'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-06-01'),
                1,
                'team'
            ],
            [
                new \DateTime('2019-06-03'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-06-01'),
                12,
                'Central Tech Support'
            ],
            [
                new \DateTime('2019-06-03'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-06-01'),
                3,
                'Test'
            ],
            [
                new \DateTime('2019-06-03'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-06-03'),
                3,
                'Test'
            ],
            [
                new \DateTime('2019-06-03'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-06-01'),
                6,
                'Test'
            ],

        ];
    }

    /**
     * @dataProvider dataProviderTestPassValidate
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @param \DateTimeInterface $startPeriod
     * @param int $count
     * @param string $team
     * @throws \Exception
     */
    public function testPassValidate(\DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        \DateTimeInterface $startPeriod,
        int $count,
        string $team)
    {
        /** @var Sdt|MockObject $valueMock */
        $valueMock = $this->createMock(Sdt::class);
        $valueMock->method('getAtOwnExpense')->willReturn(false);
        /** @var SdtPeriod|MockObject $constraintMock */
        $constraintMock = $this->createMock(SdtPeriod::class);
        /** @var HolidayService|MockObject $holidayService */
        $holidayService = $this->createMock(HolidayService::class);
        /** @var UserInfoRepository|MockObject $userInfoRepositoryMock */
        $userInfoRepositoryMock = $this->createMock(UserInfoRepository::class);
        /** @var EndDateOfSdtCalculator|MockObject $endDateCalculator */
        $endDateCalculator = $this->createMock(EndDateOfSdtCalculator::class);
        /** @var UserInformationService|MockObject $userInfoService */
        $userInfoService = $this->createMock(UserInformationService::class);
        /** @var Sdt|MockObject $sdt */
        $sdt = $this->createMock(Sdt::class);
        /** @var SdtCollection|MockObject $sdtCollection */
        $sdtCollection = $this->createMock(SdtCollection::class);
        /** @var UserInfo|MockObject $userInfo */
        $userInfo = $this->createMock(UserInfo::class);

        $sdt->method('getCreateDate')->willReturn($startPeriod);
        $sdt->method('getCount')->willReturn($count);
        $userInfoService->method('getAllUserSdt')->willReturn($sdtCollection);
        $sdtCollection->method('getItems')->willReturn([$sdt]);
        $userInfo->method('getSubTeam')->willReturn($team);

        $object = new SdtPeriodValidator($endDateCalculator, $userInfoRepositoryMock, $holidayService, $userInfoService);

        $object->initialize($this->contextMock);
        $constraintMock->method('getOldSdtEntity')->willReturn($valueMock);

        $valueMock->method('getCreateDate')->willReturn($startDate);
        $endDateCalculator->method('calculate')->willReturn($endDate);

        $this->violationBuilderMock->expects(self::never())->method('addViolation');
        $this->contextMock->expects(self::never())->method('buildViolation')->willReturn($this->violationBuilderMock);
        $object->validate($valueMock, $constraintMock);

    }

    public function dataProviderTestPassValidate()
    {
        return [
            [
                new \DateTime('2019-06-01'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-06-08'),
                1,
                'team'
            ],
            [
                new \DateTime('2019-06-01'),
                new \DateTime('2019-06-07'),
                new \DateTime('2019-05-27'),
                2,
                'Central Tech Support'
            ],
        ];
    }
}
