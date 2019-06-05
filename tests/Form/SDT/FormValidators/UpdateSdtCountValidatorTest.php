<?php

namespace App\Form\SDT\FormValidators;

use App\Entity\Sdt;
use App\Service\Sdt\Create\FormValidators\SdtCount;
use App\Service\User\Sdt\LeftSdtCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class UpdateSdtCountValidatorTest extends TestCase
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

    public function testValidate(int $value, float $calculated)
    {
        /** @var Sdt|MockObject $valueMock */
        $valueMock = $this->createMock(Sdt::class);
        $valueMock->method('getAtOwnExpense')->willReturn(false);
        /** @var SdtCount|MockObject $constraintMock */
        $constraintMock = $this->createMock(UpdateSdtCount::class);

        /** @var LeftSdtCalculator|MockObject $leftSdtCalculatorMock */
        $leftSdtCalculatorMock = $this->createMock(LeftSdtCalculator::class);
        $object = new UpdateSdtCountValidator($leftSdtCalculatorMock);
        $object->initialize($this->contextMock);
        $constraintMock->method('getOldSdtEntity')->willReturn($valueMock);
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
                -1,
            ],
        ];
    }
    /**
     * @dataProvider dataProviderTestPassValidate
     * @param int $value
     * @param float $calculated
     * @throws ReflectionException
     */

    public function testPassValidate(int $value, float $calculated)
    {
        /** @var Sdt|MockObject $valueMock */
        $valueMock = $this->createMock(Sdt::class);
        $valueMock->method('getAtOwnExpense')->willReturn(false);
        /** @var SdtCount|MockObject $constraintMock */
        $constraintMock = $this->createMock(UpdateSdtCount::class);

        /** @var LeftSdtCalculator|MockObject $leftSdtCalculatorMock */
        $leftSdtCalculatorMock = $this->createMock(LeftSdtCalculator::class);
        $object = new UpdateSdtCountValidator($leftSdtCalculatorMock);
        $object->initialize($this->contextMock);
        $constraintMock->method('getOldSdtEntity')->willReturn($valueMock);
        $leftSdtCalculatorMock->method('calculate')->willReturn($calculated);
        $valueMock->method('getCount')->willReturn($value);

        $this->violationBuilderMock->expects($this->never())->method('addViolation');
        $this->contextMock->expects($this->never())->method('buildViolation')->willReturn($this->violationBuilderMock);
        $object->validate($valueMock, $constraintMock);
    }
    public function dataProviderTestPassValidate()
    {
        return [
            [
                3,
                0,
            ],
        ];
    }
}
