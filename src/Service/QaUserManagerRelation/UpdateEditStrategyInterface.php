<?php

namespace App\Service\QaUserManagerRelation;

use App\Entity\QaUserManagerRelation;
use App\Entity\User;

interface UpdateEditStrategyInterface
{
    public function makeAction(
        QaUserManagerRelation $qaUserManagerRelation,
        User $qaUser
    ): QaUserManagerRelation;
}
