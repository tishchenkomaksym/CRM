<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 07.03.2019
 * Time: 14:01
 */

namespace App\Service\PhpDeveloperManagerRelation;

use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\User;

class PhpDeveloperManagerRelationCRUDContext
{
    /**
     * @var UpdateEditStrategyInterface
     */
    private $CRUDStrategy;

    public function __construct(UpdateEditStrategyInterface $CRUDStrategy)
    {
        $this->CRUDStrategy = $CRUDStrategy;
    }

    public function makeAction(
        PhpDeveloperManagerRelation $phpDeveloperManagerRelation,
        User $phpDeveloperUser
    ): PhpDeveloperManagerRelation {
        return $this->CRUDStrategy->makeAction($phpDeveloperManagerRelation, $phpDeveloperUser);
    }
}
