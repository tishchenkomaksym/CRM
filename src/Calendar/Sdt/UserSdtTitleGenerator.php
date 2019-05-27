<?php


namespace App\Calendar\Sdt;


use App\Entity\Sdt;

class UserSdtTitleGenerator implements SdtTitleGeneratorInterface
{

    public function getTitle(Sdt $sdt): string
    {
        return 'SDT';
    }
}