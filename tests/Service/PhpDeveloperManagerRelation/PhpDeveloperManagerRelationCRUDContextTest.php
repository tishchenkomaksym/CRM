<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 11:16
 */

namespace App\Service\PhpDeveloperManagerRelation;

use App\Entity\PhpDeveloperManagerRelation;
use App\Entity\User;
use App\Service\PhpDeveloperManagerRelation\Create\PhpDeveloperManagerRelationCreateStrategy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PhpDeveloperManagerRelationCRUDContextTest extends TestCase
{

    public function testMakeAction()
    {
        $relation = new PhpDeveloperManagerRelation();
        /** @var PhpDeveloperManagerRelationCreateStrategy|MockObject $mock */
        $mock = $this->createMock(PhpDeveloperManagerRelationCreateStrategy::class);
        $mock->expects($this->once())->method('makeAction')->willReturn(
            $relation
        );

        $context = new PhpDeveloperManagerRelationCRUDContext($mock);
        $user = new User();
        $relation->setPhpDeveloper($user);
        $result = $context->makeAction($relation, $user);
        $this->assertEquals($user, $result->getPhpDeveloper());
        $this->assertEquals($relation, $result);
    }
}
