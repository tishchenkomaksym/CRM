<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 11:30
 */

namespace App\Data\Sdt\Mail;


class DeleteSdtMailData extends BaseSdtMailData
{
    private $daysCount;
    private $fromDate;
    private $toDate;

    public function __construct(string $userName, string $fromDate, string $toDate, $daysCount)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->daysCount = $daysCount;
        BaseSdtMailData::__construct($userName);
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
}
