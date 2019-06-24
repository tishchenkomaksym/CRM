<?php

namespace App\Service\User\QaAgent\QaSkill;

use App\Entity\PhpDeveloperLevel;
use App\Entity\QaJiraComponent;
use App\Entity\User;
use App\Repository\QaRequiredJiraComponentHoursRepository;
use App\Service\User\QaAgent\DataProvider\QaJiraHoursDataProvider;

class QaJiraHoursRowBuilder
{
    /**
     * @var QaRequiredJiraComponentHoursRepository
     */
    private $jiraRequiredHoursRepository;
    /**
     * @var QaJiraHoursDataProvider
     */
    private $jiraHoursDataProvider;


    public function __construct(
        QaRequiredJiraComponentHoursRepository $jiraRequiredHoursRepository,
        QaJiraHoursDataProvider $jiraHoursDataProvider
    )
    {
        $this->jiraRequiredHoursRepository = $jiraRequiredHoursRepository;
        $this->jiraHoursDataProvider = $jiraHoursDataProvider;

    }

    /**
     * @param QaJiraHoursRow $qaJiraHoursRow
     * @param QaJiraComponent $component
     * @return void
     */
    public function buildTitle(QaJiraHoursRow $qaJiraHoursRow, QaJiraComponent $component): void
    {
        $qaJiraHoursRow->setTitle($component->getTitle());
    }
    /**
     * @param PhpDeveloperLevel $level
     * @param QaJiraHoursRow $qaJiraHoursRow
     * @param QaJiraComponent $component
     * @return void
     */
    public function buildRequiredHours(
        ?PhpDeveloperLevel $level,
        QaJiraHoursRow $qaJiraHoursRow,
        QaJiraComponent $component): void
    {
        $requiredHours = $this->jiraRequiredHoursRepository->findOneBy(['jiraComponent'=> $component, 'qaLevel' => $level]);
        if ($requiredHours !== null) {
            $qaJiraHoursRow->setRequiredHours($requiredHours->getRequiredHours());
        }
    }
    /**
     * @param User $user
     * @param QaJiraHoursRow $qaJiraHoursRow
     * @param QaJiraComponent $component
     * @return void
     */
    public function buildActualHours(User $user, QaJiraHoursRow $qaJiraHoursRow, QaJiraComponent $component): void
    {
        //Update this later with right using dataProvider logic
        $actualHours = $this->jiraHoursDataProvider;
        if ($actualHours !== null) {
            $qaJiraHoursRow->setActualHours($actualHours->getActualHours());
        }
    }


    public function getResult(QaJiraComponent $component, User $user): QaJiraHoursRow {
        $qaJiraHoursRow = new QaJiraHoursRow();
        $this->buildTitle($qaJiraHoursRow, $component);
        $this->buildRequiredHours(
            $user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel(),
            $qaJiraHoursRow,
            $component);
        $this->buildActualHours($user, $qaJiraHoursRow, $component);

        return $qaJiraHoursRow;
    }
}

