<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:25 AM
 */

namespace App\Calendar;

use App\Entity\Sdt;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SdtCalendarEventItemBuilder
{
    /**
     * @var Sdt
     */
    private $sdt;
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;
    /**
     * @var User
     */
    private $user;

    public function __construct(Sdt $sdt, \Symfony\Bundle\FrameworkBundle\Routing\Router $router, User $user)
    {
        $this->sdt = $sdt;
        $this->router = $router;
        $this->user = $user;
    }

    public function build(): CalendarItem
    {
        $calendarItem = new CalendarItem();
        $createDate = $this->sdt->getCreateDate();
        if ($createDate) {
            $calendarItem->start = $createDate->format('Y-m-d');
            $sdtCount = $this->sdt->getCount();
            $calculatedSdtCount = $sdtCount > 0 ? $sdtCount : $sdtCount * -1;
            $calendarItem->end = date_modify($createDate, '+' . $calculatedSdtCount . ' weekdays')->format('Y-m-d');
        }
        $calendarItem->title = 'SDT';
        //TODO: add TOM role check
        if ($this->user->getId() === $this->sdt->getId()) {
            $calendarItem->url = $this->router->generate(
                'sdt_edit',
                [$this->sdt->getId()],
                UrlGeneratorInterface::ABSOLUTE_PATH
            );
        }
        return $calendarItem;
    }
}
