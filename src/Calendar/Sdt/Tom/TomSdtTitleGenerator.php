<?php


namespace App\Calendar\Sdt\Tom;


use App\Calendar\Sdt\SdtTitleGeneratorInterface;
use App\Entity\Sdt;

class TomSdtTitleGenerator implements SdtTitleGeneratorInterface
{
    public function getTitle(Sdt $sdt): string
    {
        return 'SDT ' . $sdt->getUser()->getName();
    }
}