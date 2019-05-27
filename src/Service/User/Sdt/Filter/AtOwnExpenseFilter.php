<?php


namespace App\Service\User\Sdt\Filter;


use App\Entity\Sdt;

class AtOwnExpenseFilter
{
    /**
     * @param Sdt[] $array
     * @return Sdt[]
     */
    public function filter(array $array): array
    {
        return array_filter($array, static function (Sdt $item) {
            return $item->getAtOwnExpense();
        });
    }
}