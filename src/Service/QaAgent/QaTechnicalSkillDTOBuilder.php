<?php

namespace App\Service\QaAgent;

use App\Entity\User;
use App\Repository\QaRequiredSkillTestMarkRepository;
use App\Service\User\QaAgent\QaSkill\QaSkillRowBuilder;

class QaTechnicalSkillDTOBuilder
{
    /**
     * @var QaSkillRowBuilder
     */
    private $qaSkillRowBuilder;
    /**
     * @var QaRequiredSkillTestMarkRepository
     */
    private $qaRequiredSkillTestMarkRepository;

    public function __construct(
        QaSkillRowBuilder $qaSkillRowBuilder,
        QaRequiredSkillTestMarkRepository $qaRequiredSkillTestMarkRepository
    )
    {
        $this->qaSkillRowBuilder = $qaSkillRowBuilder;
        $this->qaRequiredSkillTestMarkRepository = $qaRequiredSkillTestMarkRepository;
    }

    /**
     * @param array $skillTests
     * @param User $user
     * @return void
     */
    public function buildSkillRows(array $skillTests, User $user): void
    {
        $level = $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel();

        if ($level !== null) {
            $skillTests[] = $this->qaRequiredSkillTestMarkRepository->findBy(['qaLevel' => $level]);
            foreach ($skillTests as $skillTest) {
                if($skillTest->getType() === 'technical') {
                     $skillTests[] = $this->qaSkillRowBuilder->getResult($skillTest->getTest(), $user);
                }
            }
        }
    }
    public function buildJiraHoursRows(): void
    {

    }
    public function getResult(User $user)
    {
        $skillDTO = new QaTechnicalSkillDTO();
        $skillTests = array();
        //$jiraHours = array();
        $this->buildSkillRows($skillTests, $user);
        //$this->buildJiraHoursRows();
        $skillDTO->setSkillRows($skillTests);
        //$skillDTO->setJiraHours($jiraHours);
        return $skillDTO;
    }
}
