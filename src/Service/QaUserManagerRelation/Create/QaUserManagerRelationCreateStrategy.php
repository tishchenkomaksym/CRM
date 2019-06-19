<?php

namespace App\Service\QaUserManagerRelation\Create;

use App\Entity\QaUserManagerRelation;
use App\Entity\User;
use App\Service\QaUserManagerRelation\UpdateEditStrategyInterface;
use App\Service\User\QaManager\QaManagerService;
use Doctrine\Common\Persistence\ObjectManager;

class QaUserManagerRelationCreateStrategy implements UpdateEditStrategyInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var QaManagerService
     */
    private $managerService;

    public function __construct(ObjectManager $objectManager, QaManagerService $managerService)
    {
        $this->objectManager = $objectManager;
        $this->managerService = $managerService;
    }


    public function makeAction(
        QaUserManagerRelation $qaUserManagerRelation,
        User $qa
    ): QaUserManagerRelation {
        $manager = $qaUserManagerRelation->getQaManager();
        if ($manager !== null && $manager->getQaUserManagerRelations()->count() === 0) {
            $manager = $this->managerService->addManagerRole($qaUserManagerRelation->getQaManager());
            $qaUserManagerRelation->setQaManager($manager);
        }
        $qaUserManagerRelation->setQaUser($qa);
        $this->objectManager->persist($qaUserManagerRelation);
        $this->objectManager->flush();
        return $qaUserManagerRelation;
    }
}
