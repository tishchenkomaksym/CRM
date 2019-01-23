<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:25 AM
 */

namespace App\Calendar;

use App\Entity\Holiday;
use App\Entity\Sdt;
use App\Entity\User;
use App\Service\HolidayService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SdtCalendarEventItemBuilder
{
    /**
     * @var Sdt
     */
    private $sdt;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var User
     */
    private $user;
    /**
     * @var HolidayService
     */
    private $holidayService;

    /**
     * SdtCalendarEventItemBuilder constructor.
     * @param Sdt $sdt
     * @param Router $router
     * @param User $user
     * @param Holiday[] $holidays
     */
    public function __construct(Sdt $sdt, Router $router, User $user, HolidayService $holidayService)
    {
        $this->sdt = $sdt;
        $this->router = $router;
        $this->user = $user;
        $this->holidayService = $holidayService;
    }

    public function build(): CalendarItem
    {
        $calendarItem = new CalendarItem();
        $createDate = $this->sdt->getCreateDate();
        if ($createDate) {
            $calendarItem->start = $createDate->format('Y-m-d');
            $sdtCount = $this->sdt->getCount();
            $calculatedSdtCount = $sdtCount > 0 ? $sdtCount : $sdtCount * -1;
            if ($createDate instanceof \DateTime) {
                $endDate = date_modify($createDate, '+' . $calculatedSdtCount . ' weekdays');
                $holidaysCount = count($this->holidayService->getHolidayBetweenDate($createDate, $endDate));
                if ($holidaysCount > 0) {
                    $endDate = date_modify($endDate, '+' . $holidaysCount . ' weekdays');
                }
                $calendarItem->end = $endDate->format('Y-m-d');
            }
        }
        $calendarItem->title = 'SDT';
        //TODO: add TOM role check
        if ($this->user->getId() === $this->sdt->getId()) {
            $calendarItem->url = $this->router->generate(
                'sdt_edit',
                ['id' => $this->sdt->getId()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            );
        }
        return $calendarItem;
    }
}
