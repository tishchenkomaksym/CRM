<?php

namespace App\Service\QaAgent;

use App\Entity\User;
use App\Repository\QaRequiredSkillTestMarkRepository;
use App\Service\User\QaAgent\QaSkill\QaSkillRowBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QaTechnicalSkillDTOBuilderTest extends TestCase
{

    public function testBuildSkillRows()
    {
        /** @var User|MockObject $userMock */
        $userMock = $this->createMock(User::class);
        /** @var QaTechnicalSkillDTO|MockObject $skillDTOMock */
        /** @var QaSkillRowBuilder|MockObject $qaSkillRowBuilder */
        $qaSkillRowBuilder = $this->createMock(QaSkillRowBuilder::class);
        /** @var QaRequiredSkillTestMarkRepository|MockObject $qaRequiredSkillTestMarkRepository */
        $qaRequiredSkillTestMarkRepository = $this->createMock(QaRequiredSkillTestMarkRepository::class);


        $object = new QaTechnicalSkillDTOBuilder($qaSkillRowBuilder, $qaRequiredSkillTestMarkRepository);
        $result = $object->getResult($userMock);


    }
}
