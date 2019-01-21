<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/21/2019
 * Time: 4:04 PM
 */

namespace App\Service;

use App\Repository\HolidayRepository;

class HolidayService
{
    /**
     * @var \App\Entity\Holiday[]
     */
    private $holidays;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->holidays = $holidayRepository->findAll();
    }

    public function getHolidayBetweenDate(\DateTimeInterface $from, \DateTimeInterface $to): array
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

    /**
     * @return \App\Entity\Holiday[]
     */
    public function getHolidays(): array
    {
        return $this->holidays;
    }
}
