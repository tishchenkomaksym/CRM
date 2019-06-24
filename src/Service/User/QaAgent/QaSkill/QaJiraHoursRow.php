<?php

namespace App\Service\User\QaAgent\QaSkill;

class QaJiraHoursRow
{
    /*** @var string */
    private $title = '';
    /*** @var float */
    private $actualHours = 0;
    /*** @var float */
    private $requiredHours = 0;

    public function setTitle(string $componentTitle): QaJiraHoursRow
    {
        $this->title = $componentTitle;
        return $this;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setActualHours(float $hours): QaJiraHoursRow
    {
        $this->actualHours = $hours;
        return $this;
    }
    public function getActualHours(): int {
        return $this->actualHours;
    }
    public function setRequiredHours(float $hours): QaJiraHoursRow
    {
        $this->requiredHours = $hours;
        return $this;
    }
    public function getRequiredHours(): float
    {
        return $this->requiredHours;
    }
}
