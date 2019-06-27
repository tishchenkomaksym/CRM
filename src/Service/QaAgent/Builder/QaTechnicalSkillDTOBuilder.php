<?php

namespace App\Service\QaAgent\Builder;

use App\Entity\User;
use App\Repository\QaJiraComponentRepository;
use App\Repository\QaSkillTestRepository;
use App\Service\QaAgent\QaTechnicalSkillDTO;
use App\Service\User\QaAgent\QaSkill\QaJiraHoursRowBuilder;
use App\Service\User\QaAgent\QaSkill\QaSkillRowBuilder;

class QaTechnicalSkillDTOBuilder
{
    /**
     * @var QaSkillRowBuilder
     */
    private $qaSkillRowBuilder;
    /**
     * @var QaSkillRowBuilder
     */
    private $qaJiraHoursRowBuilder;
    /**
     * @var QaSkillTestRepository
     */
    private $qaSkillTestRepository;
    /**
     * @var QaJiraComponentRepository
     */
    private $qaJiraComponentRepository;

    public function __construct(
        QaSkillRowBuilder $qaSkillRowBuilder,
        QaJiraHoursRowBuilder $qaJiraHoursRowBuilder,
        QaSkillTestRepository $qaSkillTestRepository,
        QaJiraComponentRepository $qaJiraComponentRepository
    )
    {
        $this->qaSkillRowBuilder = $qaSkillRowBuilder;
        $this->qaJiraHoursRowBuilder = $qaJiraHoursRowBuilder;
        $this->qaSkillTestRepository = $qaSkillTestRepository;
        $this->qaJiraComponentRepository = $qaJiraComponentRepository;
    }

    /**
     * @param User $user
     * @return array
     */
    public function buildSkillRows(User $user): array
    {
        $level = $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel();
        if ($level !== null) {
            $skillTests[] = $this->qaSkillTestRepository->findBy(['type' => 'technical']);
            $result = array();
            foreach ($skillTests as $test) {
                foreach ($test as $item) {
                    $result[] = $this->qaSkillRowBuilder->getResult($item, $user);
                }
            }

        }
        return $result;
    }
    /**
     * @param User $user
     * @return array
     */
    public function buildJiraHoursRows(User $user): array
    {
        $level = $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel();
        if ($level !== null) {
            $jiraComponents[] = $this->qaJiraComponentRepository->findAll();
            $result = array();
            foreach ($jiraComponents as $component) {
                foreach ($component as $item) {
                    $result[] = $this->qaJiraHoursRowBuilder->getResult($item, $user);
                }
            }
        }
        return $result;

    }
    public function getResult(User $user)
    {
        $skillDTO = new QaTechnicalSkillDTO();
        $skillTests = $this->buildSkillRows($user);
        $jiraHours = $this->buildJiraHoursRows($user);
        $skillDTO->setSkillRows($skillTests);
        $skillDTO->setJiraHours($jiraHours);
        return $skillDTO;
    }
}
