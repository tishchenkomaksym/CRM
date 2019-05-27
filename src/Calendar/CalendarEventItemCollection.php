<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:42 AM
 */

namespace App\Calendar;

class CalendarEventItemCollection
{
    /**
     * @var CalendarItem[]
     */
    protected $collection = [];

    public function add(CalendarItem $item)
    {
        $this->collection[] = $item;
    }

    public function toJson()
    {
        $array = [];
        foreach ($this->collection as $calendarItem) {
            $array[] = get_object_vars($calendarItem);
        }
        return json_encode(['events' => $array]);
    }
}
