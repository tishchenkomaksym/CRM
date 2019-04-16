<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:25 AM
 */

namespace App\Calendar;

use App\Entity\Holiday;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HolidayCalendarEventItemBuilder
{
    /**
     * @var Holiday
     */
    private $holiday;
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;

    public function __construct(Holiday $holiday, \Symfony\Bundle\FrameworkBundle\Routing\Router $router)
    {
        $this->holiday = $holiday;
        $this->router = $router;
    }

    public function build(): CalendarItem
    {
        $calendarItem = new CalendarItem();
        $createDate = $this->holiday->getDate();
        if ($createDate) {
            $calendarItem->start = $createDate->format('Y-m-d');

        }
        $calendarItem->title = $this->holiday->getTitle();
        //TODO: add TOM role check
        $calendarItem->url = $this->router->generate(
            'holiday_show',
            ['id' => $this->holiday->getId()],
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
        return $calendarItem;
    }
}
