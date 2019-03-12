<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 07.03.2019
 * Time: 14:06
 */

namespace App\Service\PhpDeveloperManagerRelation;

use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\User;

interface UpdateEditStrategyInterface
{
    public function makeAction(
        PhpDeveloperManagerRelation $phpDeveloperManagerRelation,
        User $phpDeveloperUser
    ): PhpDeveloperManagerRelation;
}
