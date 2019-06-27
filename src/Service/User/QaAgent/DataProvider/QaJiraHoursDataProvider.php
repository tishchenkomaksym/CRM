<?php

namespace App\Service\User\QaAgent\DataProvider;

class QaJiraHoursDataProvider
{
    /*** @var string */
    private $title = '';
    /*** @var float */
    private $actualHours = 0;
    /*** @var float */
    private $requiredHours = 0;

    public function setTitle(string $componentTitle): QaJiraHoursDataProvider
    {
        $this->title = $componentTitle;
        return $this;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function setActualHours(float $hours): QaJiraHoursDataProvider
    {
        $this->actualHours = $hours;
        return $this;
    }
    public function getActualHours(): int {
        return $this->actualHours;
    }
    public function setRequiredHours(float $hours): QaJiraHoursDataProvider
    {
        $this->requiredHours = $hours;
        return $this;
    }
    public function getRequiredHours(): int
    {
        return $this->requiredHours;
    }
}
