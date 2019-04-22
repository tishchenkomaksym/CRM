<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 11:30
 */

namespace App\Data\Sdt\Mail;

class NewSdtMailData extends BaseSdtMailData
{
    private $atOwnExpensive;
    private $daysCount;
    private $fromDate;
    private $toDate;
    private $actingPeople;

    public function __construct($userName, $fromDate, $toDate, $actingPeople, $daysCount, bool $atOwnExpensive)
    {
        parent::__construct($userName);
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->actingPeople = $actingPeople;
        $this->daysCount = $daysCount;
        $this->atOwnExpensive = $atOwnExpensive;
    }

    /**
     * @return mixed
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @param mixed $fromDate
     */
    public function setFromDate($fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return mixed
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @param mixed $toDate
     */
    public function setToDate($toDate): void
    {
        $this->toDate = $toDate;
    }

    /**
     * @return mixed
     */
    public function getActingPeople()
    {
        return $this->actingPeople;
    }

    /**
     * @param mixed $actingPeople
     */
    public function setActingPeople($actingPeople): void
    {
        $this->actingPeople = $actingPeople;
    }

    /**
     * @return mixed
     */
    public function getDaysCount()
    {
        return $this->daysCount;
    }

    /**
     * @param mixed $daysCount
     */
    public function setDaysCount($daysCount): void
    {
        $this->daysCount = $daysCount;
    }

    /**
     * @return bool
     */
    public function isAtOwnExpensive(): bool
    {
        return $this->atOwnExpensive;
    }

    /**
     * @param bool $atOwnExpensive
     * @return NewSdtMailData
     */
    public function setAtOwnExpensive(bool $atOwnExpensive): NewSdtMailData
    {
        $this->atOwnExpensive = $atOwnExpensive;
        return $this;
    }
}
