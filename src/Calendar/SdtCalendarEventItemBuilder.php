<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:25 AM
 */

namespace App\Calendar;

use App\Calendar\DateCalculator\DateCalculatorWithWeekends;
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
     * @param HolidayService $holidayService
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
        if ($createDate && $createDate instanceof \DateTime) {
            $calendarItem->start = $createDate->format('Y-m-d');
            $calendarItem->end = DateCalculatorWithWeekends::getDateWithOffset(
                $createDate,
                $this->sdt->getCount(),
                $this->holidayService
            )->format('Y-m-d');
        }
        $calendarItem->title = 'SDT';
        //TODO: add TOM role check
        if ($this->user->getId() === $this->sdt->getUser()->getId()) {
            $calendarItem->url = $this->router->generate(
                'sdt_show',
                ['id' => $this->sdt->getId()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            );
        }
        return $calendarItem;
    }
}
