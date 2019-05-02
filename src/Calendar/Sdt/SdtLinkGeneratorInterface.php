<?php


namespace App\Calendar\Sdt;


use App\Entity\Sdt;
use App\Entity\User;

interface  SdtLinkGeneratorInterface
{
    public function getLink(User $currentUser, Sdt $sdt): string;
}