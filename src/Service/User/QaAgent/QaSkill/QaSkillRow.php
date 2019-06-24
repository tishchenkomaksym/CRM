<?php

namespace App\Service\User\QaAgent\QaSkill;

class QaSkillRow
{
    /*** @var string */
    private $title = '';
    /*** @var int */
    private $actualPoints = 0;
    /*** @var int */
    private $requiredPoints = 0;
    /** @var string */
    private $testLink = '';

    public function setTitle(string $skillTitle): QaSkillRow
    {
        $this->title = $skillTitle;
        return $this;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setActualPoints(?int $points): QaSkillRow
    {
        $this->actualPoints = $points;
        return $this;
    }
    public function getActualPoints(): ?int {
        return $this->actualPoints;
    }
    public function setRequiredPoints(?int $points): QaSkillRow
    {
        $this->requiredPoints = $points;
        return $this;
    }
    public function getRequiredPoints(): ?int
    {
        return $this->requiredPoints;
    }
    public function setTestLink(string $link): QaSkillRow
    {
        $this->testLink = $link;
        return $this;
    }
    public function getTestLink(): string
    {
        return $this->testLink;
    }
}
