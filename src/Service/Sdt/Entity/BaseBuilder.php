<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 13:48
 */

namespace App\Service\Sdt\Entity;

use App\Entity\Sdt;
use DateTimeImmutable;
use Exception;

class BaseBuilder
{
    /**
     * @param Sdt $entity
     * @return Sdt
     * @throws Exception
     */
    public static function build(Sdt $entity): Sdt
    {
        $entity->setReportDate(new DateTimeImmutable());
        return $entity;
    }
}
