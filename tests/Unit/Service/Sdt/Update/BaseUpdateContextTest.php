<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 05.03.2019
 * Time: 14:26
 */

namespace App\Service\Sdt\Update;

use App\Entity\Sdt;
use App\Entity\SdtArchive;
use App\Service\Sdt\Exception\EmailServerNotWorking;
use PHPUnit\Framework\TestCase;

class BaseUpdateContextTest extends TestCase
{
    /**
     * @throws \App\Service\Sdt\Exception\EmailServerNotWorking
     */
    public function testUpdateSDT()
    {
        $sdt = new Sdt();
        $strategyMock = $this->createMock(BaseUpdateStrategy::class);
        $strategyMock->expects($this->once())
                     ->method('sendEmail')
                     ->willReturn(6);
        $strategyMock->expects($this->once())
                     ->method('update')
                     ->willReturn($sdt);
        $strategyMock->expects($this->once())
                     ->method('createArchive')
                     ->willReturn(New SdtArchive());

        $context = New BaseUpdateContext($strategyMock);
        $this->assertEquals($sdt, $context->updateSDT());
    }

    /**
     * @throws \App\Service\Sdt\Exception\EmailServerNotWorking
     */
    public function testUpdateSDTException(): void
    {
        $sdt = new Sdt();
        $strategyMock = $this->createMock(BaseUpdateStrategy::class);
        $strategyMock->expects($this->once())
                     ->method('sendEmail')
                     ->willReturn(0);
        $this->expectException(EmailServerNotWorking::class);
        $context = New BaseUpdateContext($strategyMock);
        $this->assertEquals($sdt, $context->updateSDT());
    }

}
