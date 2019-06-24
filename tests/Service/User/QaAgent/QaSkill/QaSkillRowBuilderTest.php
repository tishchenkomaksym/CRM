<?php

namespace App\Service\User\QaAgent\QaSkill;

use App\Entity\PhpDeveloperLevel;
use App\Entity\QaActualSkillTestMark;
use App\Entity\QaRequiredSkillTestMark;
use App\Entity\QaSkillTest;
use App\Entity\User;
use App\Entity\UserPhpDeveloperLevelRelation;
use App\Repository\QaActualSkillTestMarkRepository;
use App\Repository\QaRequiredSkillTestMarkRepository;
use App\Repository\QaSkillTestRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QaSkillRowBuilderTest extends TestCase
{

    public function testGetResult()
    {
        /** @var User|MockObject $userMock */
        $userMock = $this->createMock(User::class);
        /** @var QaRequiredSkillTestMark|MockObject $requiredMarkMock */
        $requiredMarkMock = $this->createMock(QaRequiredSkillTestMark::class);
        /** @var QaActualSkillTestMark|MockObject $actualMarkMock */
        $actualMarkMock = $this->createMock(QaActualSkillTestMark::class);
        /**
         * @var UserPhpDeveloperLevelRelation|MockObject $levelRelationMock
         */
        $levelRelationMock = $this->createMock(UserPhpDeveloperLevelRelation::class);
        /**
         * @var QaSkillTest|MockObject $qaSkillTestMock
         */
        $qaSkillTestMock = $this->createMock(QaSkillTest::class);
        /**
         * @var QaSkillTestRepository|MockObject $skillTestRepositoryMock
         */
        $skillTestRepositoryMock = $this->createMock(QaSkillTestRepository::class);
        $skillTestRepositoryMock->method('findOneBy')->willReturn($qaSkillTestMock);
        $qaSkillTestMock->method('getTitle')->willReturn('title');
        $qaSkillTestMock->method('getLink')->willReturn('link');
        /**
         * @var QaRequiredSkillTestMarkRepository|MockObject $requiredSkillTestMarkRepositoryMock
         */
        $requiredSkillTestMarkRepositoryMock = $this->createMock(QaRequiredSkillTestMarkRepository::class);
        $requiredSkillTestMarkRepositoryMock->method('findOneBy')->willReturn($requiredMarkMock);
        $requiredMarkMock->method('getRequiredPoints')->willReturn(30);
        /**
         * @var QaActualSkillTestMarkRepository|MockObject $actualSkillTestMarkRepositoryMock
         */
        $actualSkillTestMarkRepositoryMock = $this->createMock(QaActualSkillTestMarkRepository::class);
        $actualSkillTestMarkRepositoryMock->method('findOneBy')->willReturn($actualMarkMock);
        $actualMarkMock->method('getActualPoints')->willReturn(31);
        /**
         * @var PhpDeveloperLevel|MockObject $levelMock
         */
        $levelMock = $this->createMock(PhpDeveloperLevel::class);

        $object = new QaSkillRowBuilder(
            $skillTestRepositoryMock,
            $requiredSkillTestMarkRepositoryMock,
            $actualSkillTestMarkRepositoryMock
        );

        $userMock->method('getPhpDeveloperLevelRelation')->willReturn($levelRelationMock);
        $levelRelationMock->method('getPhpDeveloperLevel')->willReturn($levelMock);

        $assertObject = new QaSkillRow();
        $assertObject->setTitle('title');
        $assertObject->setRequiredPoints(30);
        $assertObject->setActualPoints(31);
        $assertObject->setTestLink('link');
        $assertObject->setPassed(true);

        $result = $object->getResult($qaSkillTestMock, $userMock);
        $this->assertEquals($assertObject, $result);
    }
}
