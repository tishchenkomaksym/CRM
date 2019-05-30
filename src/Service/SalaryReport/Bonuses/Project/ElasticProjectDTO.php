<?php


namespace App\Service\SalaryReport\Bonuses\Project;


use DateTime;

class ElasticProjectDTO
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $status;
    /**
     * @var DateTime
     */
    private $endDate;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ElasticProjectDTO
     */
    public function setKey(string $key): ElasticProjectDTO
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ElasticProjectDTO
     */
    public function setName(string $name): ElasticProjectDTO
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return ElasticProjectDTO
     */
    public function setStatus(string $status): ElasticProjectDTO
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     * @return ElasticProjectDTO
     */
    public function setEndDate(DateTime $endDate): ElasticProjectDTO
    {
        $this->endDate = $endDate;
        return $this;
    }


}