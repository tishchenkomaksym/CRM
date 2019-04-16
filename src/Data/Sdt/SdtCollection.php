<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 12:03 PM
 */

namespace App\Data\Sdt;

use App\Entity\Sdt;

class SdtCollection
{
    /**
     * @var Sdt[]
     */
    private $items = [];

    /**
     * SdtCollection constructor.
     * @param Sdt[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @return Sdt[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getCountSum()
    {
        $sum = 0;
        foreach ($this->items as $item) {
            $sum += $item->getCount();
        }
        return $sum;
    }
}
