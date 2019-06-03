<?php

namespace App\Service\Sdt\Create\FormValidators;

use App\Entity\Sdt;
use App\Service\User\Sdt\LeftSdtCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class SdtCountValidatorTest extends TestCase
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
     * @param int $value
     * @param float $calculated
     * @throws ReflectionException
     */

    public function testValidate(int $value, float $calculated) : void
    {
        /** @var Sdt|MockObject $valueMock */
        $valueMock = $this->createMock(Sdt::class);
        $valueMock->method('getAtOwnExpense')->willReturn(false);
        /** @var SdtCount|MockObject $constraintMock */
        $constraintMock = $this->createMock(SdtCount::class);


        $object = new SdtCountValidator();
        $object->initialize($this->contextMock);

        /** @var LeftSdtCalculator|MockObject $leftSdtCalculatorMock */
        $leftSdtCalculatorMock = $this->createMock(LeftSdtCalculator::class);
        $constraintMock->method('getLeftSdtCalculator')->willReturn($leftSdtCalculatorMock);
        $leftSdtCalculatorMock->method('calculate')->willReturn($calculated);
        $valueMock->method('getCount')->willReturn($value);

        $this->violationBuilderMock->expects(self::once())->method('addViolation');
        $this->contextMock->expects(self::once())->method('buildViolation')->willReturn($this->violationBuilderMock);
        $object->validate($valueMock, $constraintMock);
    }

    public function dataProviderTestValidate()
    {
        return [
            [
                3,
                2,
            ],
        ];
    }
    /**
     * @dataProvider dataProviderTestValidatePassed
     * @param int $value
     * @param float $calculated
     */
    public function testValidatePassed(int $value, float $calculated) : void
    {
        /** @var Sdt|MockObject $valueMock */
        $valueMock = $this->createMock(Sdt::class);
        $valueMock->method('getAtOwnExpense')->willReturn(false);
        /** @var SdtCount|MockObject $constraintMock */
        $constraintMock = $this->createMock(SdtCount::class);


        $object = new SdtCountValidator();
        $object->initialize($this->contextMock);

        /** @var LeftSdtCalculator|MockObject $leftSdtCalculatorMock */
        $leftSdtCalculatorMock = $this->createMock(LeftSdtCalculator::class);
        $constraintMock->method('getLeftSdtCalculator')->willReturn($leftSdtCalculatorMock);
        $leftSdtCalculatorMock->method('calculate')->willReturn($calculated);
        $valueMock->method('getCount')->willReturn($value);

        $this->violationBuilderMock->expects($this->never())->method('addViolation');
        $this->contextMock->expects($this->never())->method('buildViolation')->willReturn($this->violationBuilderMock);
        $object->validate($valueMock, $constraintMock);
    }
    public function dataProviderTestValidatePassed()
    {
        return [
            [
                5,
                5,
            ],
            [
                4,
                5,
            ],
        ];
    }


}
