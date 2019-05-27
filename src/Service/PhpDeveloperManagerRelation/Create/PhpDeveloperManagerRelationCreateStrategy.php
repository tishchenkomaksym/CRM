<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 07.03.2019
 * Time: 14:03
 */

namespace App\Service\PhpDeveloperManagerRelation\Create;

use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\User;
use App\Service\PhpDeveloperManagerRelation\UpdateEditStrategyInterface;
use App\Service\User\PhpManager\PhpManagerService;
use Doctrine\Common\Persistence\ObjectManager;

class PhpDeveloperManagerRelationCreateStrategy implements UpdateEditStrategyInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var PhpManagerService
     */
    private $managerService;

    public function __construct(ObjectManager $objectManager, PhpManagerService $managerService)
    {
        $this->objectManager = $objectManager;
        $this->managerService = $managerService;
    }


    public function makeAction(
        PhpDeveloperManagerRelation $phpDeveloperManagerRelation,
        User $phpDeveloperUser
    ): PhpDeveloperManagerRelation {
        $manager = $phpDeveloperManagerRelation->getManager();
        if ($manager !== null && $manager->getPhpManagerDeveloperRelations()->count() === 0) {
            $manager = $this->managerService->addManagerRole($phpDeveloperManagerRelation->getManager());
            $phpDeveloperManagerRelation->setManager($manager);
        }
        $phpDeveloperManagerRelation->setPhpDeveloper($phpDeveloperUser);
        $this->objectManager->persist($phpDeveloperManagerRelation);
        $this->objectManager->flush();
        return $phpDeveloperManagerRelation;
    }
}
