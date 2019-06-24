<?php

namespace App\Service\QaAgent\Builder;

use App\Entity\User;
use App\Repository\QaJiraComponentRepository;
use App\Service\User\QaAgent\QaSkill\QaJiraHoursRowBuilder;
use App\Service\User\QaAgent\QaSkill\QaSkillRowBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QaTechnicalSkillDTOBuilderTest extends TestCase
{

    public function testGetResult()
    {
        /** @var User|MockObject $userMock */
        $userMock = $this->createMock(User::class);
        /** @var QaSkillRowBuilder|MockObject $qaSkillRowBuilder */
        $qaSkillRowBuilder = $this->createMock(QaSkillRowBuilder::class);
        /** @var QaJiraHoursRowBuilder|MockObject $qaJiraHoursRowBuilder */
        $qaJiraHoursRowBuilder = $this->createMock(QaJiraHoursRowBuilder::class);
        /** @var QaJiraComponentRepository|MockObject $qaJiraComponentRepository */
        $qaJiraComponentRepository = $this->createMock(QaJiraComponentRepository::class);
        /** @var QaJiraComponentRepository|MockObject $qaSkillTestRepository */
        $qaSkillTestRepository = $this->createMock(QaJiraComponentRepository::class);

    }
}
