<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 07.03.2019
 * Time: 14:01
 */

namespace App\Service\QaUserManagerRelation;

use App\Entity\QaUserManagerRelation;
use App\Entity\User;

class QaUserManagerRelationCRUDContext
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
        QaUserManagerRelation $qaUserManagerRelation,
        User $qaUser
    ): QaUserManagerRelation {
        return $this->CRUDStrategy->makeAction($qaUserManagerRelation, $qaUser);
    }
}
