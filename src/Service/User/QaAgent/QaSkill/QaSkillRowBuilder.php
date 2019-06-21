<?php

namespace App\Service\User\QaAgent\QaSkill;

use App\Entity\PhpDeveloperLevel;
use App\Entity\User;
use App\Entity\QaSkillTest;
use App\Repository\QaActualSkillTestMarkRepository;
use App\Repository\QaRequiredSkillTestMarkRepository;
use App\Repository\QaSkillTestRepository;

class QaSkillRowBuilder
{
    /**
     * @var QaSkillTest
     */
    private $qaSkillTest;
    /**
     * @var QaSkillTestRepository
     */
    private $skillTestRepository;
    /**
     * @var QaRequiredSkillTestMarkRepository
     */
    private $requiredSkillTestMarkRepository;
    /**
     * @var QaActualSkillTestMarkRepository
     */
    private $actualSkillTestMarkRepository;

    public function __construct(
        QaSkillTestRepository $skillTestRepository,
        QaRequiredSkillTestMarkRepository $requiredSkillTestMarkRepository,
        QaActualSkillTestMarkRepository $actualSkillTestMarkRepository,
        QaSkillTest $qaSkillTest
    )
    {
        $this->skillTestRepository = $skillTestRepository;
        $this->requiredSkillTestMarkRepository = $requiredSkillTestMarkRepository;
        $this->actualSkillTestMarkRepository = $actualSkillTestMarkRepository;
        $this->qaSkillTest = $qaSkillTest;
    }

    /**
     * @param QaSkillRow $qaSkillRow
     * @return void
     */
    public function buildTitle(QaSkillRow $qaSkillRow): void
    {
        $test = $this->skillTestRepository->findOneBy(['test' => $this->qaSkillTest]);
        if ($test !== null) {
            $qaSkillRow->setTitle($test->getTitle());
        }
    }
    /**
     * @param PhpDeveloperLevel $level
     * @param QaSkillRow $qaSkillRow
     * @return void
     */
    public function buildRequiredMark(?PhpDeveloperLevel $level, QaSkillRow $qaSkillRow): void
    {
        $requiredMark = $this->requiredSkillTestMarkRepository->findOneBy(['test'=> $this->qaSkillTest, 'qaLevel' => $level]);
        if ($requiredMark !== null) {
            $qaSkillRow->setRequiredPoints($requiredMark->getRequiredPoints());
        }
    }
    /**
     * @param User $user
     * @param QaSkillRow $qaSkillRow
     * @return void
     */
    public function buildActualMark(User $user, QaSkillRow $qaSkillRow): void
    {
        $actualMark = $this->actualSkillTestMarkRepository->findOneBy(['test' => $this->qaSkillTest, 'user' => $user]);
        if ($actualMark !== null) {
            $qaSkillRow->setActualPoints($actualMark->getActualPoints());
        }
    }
    /**
     * @param QaSkillRow $qaSkillRow
     * @return void
     */
    public function buildTestLink(QaSkillRow $qaSkillRow): void
    {
        $test = $this->skillTestRepository->findOneBy(['id' => $this->qaSkillTest->getId()]);
        if ($test !== null) {
            $qaSkillRow->setTestLink($test->getLink());
        }
    }

    public function getResult(User $user): QaSkillRow {
        $qaSkillRow = new QaSkillRow();
        $this->buildTitle($qaSkillRow);
        $this->buildRequiredMark($user->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel(), $qaSkillRow);
        $this->buildActualMark($user, $qaSkillRow);
        $this->buildTestLink($qaSkillRow);

        return $qaSkillRow;
    }
}

