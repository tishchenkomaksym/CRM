<?php

namespace App\Service\User\QaAgent\QaSkill;

use App\Entity\PhpDeveloperLevel;
use App\Entity\User;
use App\Entity\QaSkillTest;
use App\Repository\QaJiraComponentRepository;
use App\Repository\QaRequiredJiraComponentHoursRepository;

class QaJiraHoursRowBuilder
{
    /**
     * @var QaJiraComponentRepository
     */
    private $jiraComponentRepository;
    /**
     * @var QaRequiredJiraComponentHoursRepository
     */
    private $jiraRequiredHoursRepository;
    /**
     * @var QaJiraHoursDataProvider
     */
    private $jiraHoursDataProvider;


    public function __construct(
        QaJiraComponentRepository $jiraComponentRepository,
        QaRequiredJiraComponentHoursRepository $jiraRequiredHoursRepository,
        QaJiraHoursDataProvider $jiraHoursDataProvider
    )
    {
        $this->jiraComponentRepository = $jiraComponentRepository;
        $this->jiraRequiredHoursRepository = $jiraRequiredHoursRepository;
        $this->jiraHoursDataProvider = $jiraHoursDataProvider;

    }

    /**
     * @param QaSkillRow $qaSkillRow
     * @param QaSkillTest $qaSkillTest
     * @return void
     */
    public function buildTitle(QaSkillRow $qaSkillRow, QaSkillTest $qaSkillTest): void
    {
            $qaSkillRow->setTitle($qaSkillTest->getTitle());
    }
    /**
     * @param PhpDeveloperLevel $level
     * @param QaSkillRow $qaSkillRow
     * @param QaSkillTest $qaSkillTest
     * @return void
     */
    public function buildRequiredMark(
        ?PhpDeveloperLevel $level,
        QaSkillRow $qaSkillRow,
        QaSkillTest $qaSkillTest): void
    {
        $requiredMark = $this->requiredSkillTestMarkRepository->findOneBy(['test'=> $qaSkillTest, 'qaLevel' => $level]);
        if ($requiredMark !== null) {
            $qaSkillRow->setRequiredPoints($requiredMark->getRequiredPoints());
        }
    }
    /**
     * @param User $user
     * @param QaSkillRow $qaSkillRow
     * @param QaSkillTest $qaSkillTest
     * @return void
     */
    public function buildActualMark(User $user, QaSkillRow $qaSkillRow, QaSkillTest $qaSkillTest): void
    {
        $actualMark = $this->actualSkillTestMarkRepository->findOneBy(['test' => $qaSkillTest, 'user' => $user]);
        if ($actualMark !== null) {
            $qaSkillRow->setActualPoints($actualMark->getActualPoints());
        }
    }
    /**
     * @param QaSkillRow $qaSkillRow
     * @param QaSkillTest $qaSkillTest
     * @return void
     */
    public function buildTestLink(QaSkillRow $qaSkillRow, QaSkillTest $qaSkillTest): void
    {
            $qaSkillRow->setTestLink($qaSkillTest->getLink());
    }

    public function getResult(QaSkillTest $skillTest, User $user): QaSkillRow {
        $qaSkillRow = new QaSkillRow();
        $this->buildTitle($qaSkillRow, $skillTest);
        $this->buildRequiredMark(
            $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel(),
            $qaSkillRow,
            $skillTest);
        $this->buildActualMark($user, $qaSkillRow, $skillTest);
        $this->buildTestLink($qaSkillRow, $skillTest);

        return $qaSkillRow;
    }
}

