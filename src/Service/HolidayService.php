<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/21/2019
 * Time: 4:04 PM
 */

namespace App\Service;

use App\Entity\Holiday;
use App\Repository\HolidayRepository;
use DateTime;
use DateTimeInterface;

class HolidayService
{
    /**
     * @var Holiday[]
     */
    private $holidays;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->holidays = $holidayRepository->findAll();
    }

    /**
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @return Holiday[]
     */
    public function getHolidayBetweenDate(DateTimeInterface $from, DateTimeInterface $to): array
    {
        $holidayArray = [];
        foreach ($this->holidays as $holiday) {
            $date = $holiday->getDate();
            if ($date >= $from && $date <= $to) {
                $holidayArray[] = $holiday;
            }
        }
        return $holidayArray;
    }

    public function getHolidayBetweenDateNumbers(DateTime $from, DateTime $to): array
    {

        $calculationDate1 = clone $from;
        $calculationDate1->setTime(00,00,00);


        $calculationDate2 = clone $to;
        $calculationDate2->setTime(00,00,00);

        $holidayArray = [];
        foreach ($this->holidays as $holiday) {
            $date = $holiday->getDate();
            if ($date >= $calculationDate1 && $date <= $calculationDate2) {
                $holidayArray[] = $holiday;
            }
        }
        return $holidayArray;
    }

    /**
     * @return Holiday[]
     */
    public function getHolidays(): array
    {
        return $this->holidays;
    }
}
