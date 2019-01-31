<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 31.01.2019
 * Time: 11:30
 */

namespace App\Data\Sdt\Mail;

class EditSdtMailData extends BaseSdtMailData
{
    private $oldFromDate;
    private $oldToDate;
    private $newFromDate;
    private $newToDate;
    private $actingPeople;
    /**
     * @var int
     */
    private $daysCount;

    public function __construct(
        string $oldFromDate,
        string $oldToDate,
        string $newFromDate,
        string $newToDate,
        string $actingPeople,
        int $daysCount
    ) {
        $this->oldFromDate = $oldFromDate;
        $this->oldToDate = $oldToDate;
        $this->newFromDate = $newFromDate;
        $this->newToDate = $newToDate;
        $this->actingPeople = $actingPeople;
        $this->daysCount = $daysCount;
    }

    /**
     * @return mixed
     */
    public function getOldFromDate()
    {
        return $this->oldFromDate;
    }

    /**
     * @param mixed $oldFromDate
     */
    public function setOldFromDate($oldFromDate): void
    {
        $this->oldFromDate = $oldFromDate;
    }

    /**
     * @return mixed
     */
    public function getOldToDate()
    {
        return $this->oldToDate;
    }

    /**
     * @param mixed $oldToDate
     */
    public function setOldToDate($oldToDate): void
    {
        $this->oldToDate = $oldToDate;
    }

    /**
     * @return mixed
     */
    public function getNewFromDate()
    {
        return $this->newFromDate;
    }

    /**
     * @param mixed $newFromDate
     */
    public function setNewFromDate($newFromDate): void
    {
        $this->newFromDate = $newFromDate;
    }

    /**
     * @return mixed
     */
    public function getNewToDate()
    {
        return $this->newToDate;
    }

    /**
     * @param mixed $newToDate
     */
    public function setNewToDate($newToDate): void
    {
        $this->newToDate = $newToDate;
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
     * @return int
     */
    public function getDaysCount(): int
    {
        return $this->daysCount;
    }

    /**
     * @param int $daysCount
     */
    public function setDaysCount(int $daysCount): void
    {
        $this->daysCount = $daysCount;
    }
}