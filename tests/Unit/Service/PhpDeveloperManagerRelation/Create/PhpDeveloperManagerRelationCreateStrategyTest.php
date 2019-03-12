<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 07.03.2019
 * Time: 14:32
 */

namespace App\Service\PhpDeveloperManagerRelation\Create;

use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\User;
use App\Service\User\PhpManager\PhpManagerService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PhpDeveloperManagerRelationCreateStrategyTest extends TestCase
{
    /**
     * @var EntityManager|MockObject
     */
    protected $objectManager;
    /**
     * @var MockObject|PhpManagerService
     */
    protected $phpManagerService;

    public function setUp()
    {
        $this->objectManager = $this->createMock(EntityManager::class);
        $this->phpManagerService = $this->createMock(PhpManagerService::class);

    }

    public function testMakeAction()
    {
        $this->objectManager->expects($this->once())->method('persist');
        $this->objectManager->expects($this->once())->method('flush');
        $strategy = new PhpDeveloperManagerRelationCreateStrategy($this->objectManager, $this->phpManagerService);
        $managerUser = new User();

        $newPhpDeveloperRelation = new PhpDeveloperManagerRelation();

        $newPhpDeveloperRelation->setManager($managerUser);

        //User to add
        $phpDeveloperUser = new User();
        $managerUser->addPhpManagerDeveloperRelation(new PhpDeveloperManagerRelation());
        $relation = $strategy->makeAction($newPhpDeveloperRelation, $phpDeveloperUser);
        $this->assertEquals($phpDeveloperUser, $relation->getPhpDeveloper());
        $this->assertEquals($managerUser, $relation->getManager());
    }

    public function testMakeActionWithNewRoleForManager()
    {
        $this->objectManager->expects($this->once())->method('persist');
        $this->objectManager->expects($this->once())->method('flush');
        $strategy = new PhpDeveloperManagerRelationCreateStrategy($this->objectManager, $this->phpManagerService);

        $managerUser = new User();

        $this->phpManagerService->expects($this->once())->method('addManagerRole')->willReturn($managerUser);
        $newPhpDeveloperRelation = new PhpDeveloperManagerRelation();

        $newPhpDeveloperRelation->setManager($managerUser);

        //User to add
        $phpDeveloperUser = new User();
        $relation = $strategy->makeAction($newPhpDeveloperRelation, $phpDeveloperUser);
        $this->assertEquals($phpDeveloperUser, $relation->getPhpDeveloper());
        $this->assertEquals($managerUser, $relation->getManager());
    }
}
