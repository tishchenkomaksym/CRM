<?php

namespace App\Service\User\QaAgent\QaSkill;

class QaSkillRow
{
    /*** @var string */
    private $title = '';
    /*** @var int */
    private $actualPoints;
    /*** @var int */
    private $requiredPoints;
    /** @var string */
    private $testLink = '';
    /** @var bool */
    private $passed = false;

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
    public function setPassed(bool $passed): QaSkillRow
    {
        $this->passed = $passed;
        return $this;
    }
    public function getPassed(): bool
    {
        return $this->passed;
    }
}
