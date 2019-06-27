<?php

namespace App\Service\QaAgent;

class QaTechnicalSkillDTO
{
    /**
     * @var array
     */
    private $qaSkillRows;
    private $jiraHours;

    /**
     * @return array
     */
    public function getSkillRows(): array
    {
        return $this->qaSkillRows;
    }

    /**
     * @param array $qaSkillRows
     * @return QaTechnicalSkillDTO
     */
    public function setSkillRows(array $qaSkillRows): QaTechnicalSkillDTO
    {
        $this->qaSkillRows = $qaSkillRows;
        return $this;
    }
    /**
     * @return array
     */
    public function getJiraHours(): array
    {
        return $this->jiraHours;
    }

    /**
     * @param array $jiraHours
     * @return QaTechnicalSkillDTO
     */
    public function setJiraHours(array $jiraHours): QaTechnicalSkillDTO
    {
        $this->jiraHours = $jiraHours;
        return $this;
    }
}