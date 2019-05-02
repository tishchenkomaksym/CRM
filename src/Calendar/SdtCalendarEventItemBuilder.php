<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:25 AM
 */

namespace App\Calendar;

use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
use App\Calendar\Sdt\SdtLinkGeneratorInterface;
use App\Calendar\Sdt\SdtTitleGeneratorInterface;
use App\Entity\Sdt;
use App\Entity\User;
use App\Service\HolidayService;
use DateTime;

class SdtCalendarEventItemBuilder
{
    /**
     * @var HolidayService
     */
    private $holidayService;
    /**
     * @var SdtLinkGeneratorInterface
     */
    private $linkGenerator;
    /**
     * @var SdtTitleGeneratorInterface
     */
    private $titleGenerator;

    /**
     * SdtCalendarEventItemBuilder constructor.
     * @param HolidayService $holidayService
     * @param SdtLinkGeneratorInterface $linkGenerator
     * @param SdtTitleGeneratorInterface $titleGenerator
     */
    public function __construct(
        HolidayService $holidayService,
        SdtLinkGeneratorInterface $linkGenerator,
        SdtTitleGeneratorInterface $titleGenerator
    )
    {
        $this->holidayService = $holidayService;
        $this->linkGenerator = $linkGenerator;
        $this->titleGenerator = $titleGenerator;
    }

    public function build(Sdt $sdt,
                          User $user): CalendarItem
    {
        $calendarItem = new CalendarItem();
        $createDate = $sdt->getCreateDate();
        if ($createDate && $createDate instanceof DateTime) {
            $calendarItem->start = $createDate->format('Y-m-d');
            $calendarItem->end = DateCalculatorWithWeekends::getDateWithOffset(
                $createDate,
                $sdt->getCount(),
                $this->holidayService
            );
            $calendarItem->end = $calendarItem->end->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        }
        $calendarItem->title = $this->titleGenerator->getTitle($sdt);
        $calendarItem->url = $this->linkGenerator->getLink($user, $sdt);
        return $calendarItem;
    }
}
