<?php


namespace App\Calendar\Sdt;


use App\Entity\Sdt;

interface SdtTitleGeneratorInterface
{
    public function getTitle(Sdt $sdt): string;
}