<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 05.03.2019
 * Time: 12:43
 */

namespace App\Service\Sdt\Create;

use App\Entity\Sdt;
use App\Service\Sdt\Exception\EmailServerNotWorking;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BaseCreateContextTest extends TestCase
{

    /**
     * @throws \App\Service\Sdt\Exception\EmailServerNotWorking
     */
    public function testCreateSdt()
    {
        $sdt = new Sdt();
        /** @var BaseCreateStrategy|MockObject $strategyMock */
        $strategyMock = $this->createMock(BaseCreateStrategy::class);
        $strategyMock->expects($this->once())
                     ->method('sendEmail')
                     ->willReturn(6);
        $strategyMock->expects($this->once())
                     ->method('create')
                     ->willReturn($sdt);
        $context = New BaseCreateContext($strategyMock);
        $this->assertEquals($sdt, $context->createSdt());
    }

    /**
     * @throws \App\Service\Sdt\Exception\EmailServerNotWorking
     */
    public function testCreateSdtException(): void
    {
        $sdt = new Sdt();
        /** @var BaseCreateStrategy|MockObject $strategyMock */
        $strategyMock = $this->createMock(BaseCreateStrategy::class);
        $strategyMock->expects($this->once())
                     ->method('sendEmail')
                     ->willReturn(0);
        $this->expectException(EmailServerNotWorking::class);
        $context = New BaseCreateContext($strategyMock);
        $this->assertEquals($sdt, $context->createSdt());
    }
}
