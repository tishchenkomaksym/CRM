<?php

namespace App\Service\User\QaAgent\QaSkill;

use App\Entity\PhpDeveloperLevel;
use App\Entity\User;
use App\Entity\QaSkillTest;
use App\Repository\PhpDeveloperLevelRepository;
use App\Repository\QaActualSkillTestMarkRepository;
use App\Repository\QaRequiredSkillTestMarkRepository;
use App\Repository\QaSkillTestRepository;

class QaSkillRowBuilder
{
    /**
     * @var QaRequiredSkillTestMarkRepository
     */
    private $requiredSkillTestMarkRepository;
    /**
     * @var QaActualSkillTestMarkRepository
     */
    private $actualSkillTestMarkRepository;
    /**
     * @var PhpDeveloperLevelRepository
     */
    private $phpDeveloperLevelRepository;

    public function __construct(
        QaRequiredSkillTestMarkRepository $requiredSkillTestMarkRepository,
        QaActualSkillTestMarkRepository $actualSkillTestMarkRepository,
        PhpDeveloperLevelRepository $phpDeveloperLevelRepository
    )
    {
        $this->requiredSkillTestMarkRepository = $requiredSkillTestMarkRepository;
        $this->actualSkillTestMarkRepository = $actualSkillTestMarkRepository;
        $this->phpDeveloperLevelRepository = $phpDeveloperLevelRepository;
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
        if(isset($level)) {
            $userLevel = $this->phpDeveloperLevelRepository
                ->findOneBy(['title' => $level->getTitle()]);
            if($userLevel !== null) {
                $nextLevel = $userLevel->getNextLevel();
            } else {
                $nextLevel = $userLevel;
            }
            $requiredMark = $this->requiredSkillTestMarkRepository->findOneBy(['test'=> $qaSkillTest, 'qaLevel' => $nextLevel]);
            if ($requiredMark !== null) {
                $qaSkillRow->setRequiredPoints($requiredMark->getRequiredPoints());
            }
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
        $actualMark = $this->actualSkillTestMarkRepository->findOneBy(['qaSkillTest' => $qaSkillTest, 'user' => $user]);
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
    /**
     * @param QaSkillRow $qaSkillRow
     * @return void
     */
    public function buildPassed(QaSkillRow $qaSkillRow): void
    {
        $actual = $qaSkillRow->getActualPoints();
        $required = $qaSkillRow->getTitle();

        if($actual !== null && $required !== null &&
        $actual >= $required) {
            $qaSkillRow->setPassed(true);
        }
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
        $this->buildPassed($qaSkillRow);

        return $qaSkillRow;
    }
}

