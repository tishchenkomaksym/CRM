<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 16:47
 */

namespace App\Service\SdtArchive;

use App\Entity\Sdt;
use App\Entity\SdtArchive;
use DateTime;
use DateTimeImmutable;
use Exception;

class SdtArchiveBuilderFromSdt
{
    /**
     * @param Sdt $entity
     * @param SdtArchive $archive
     * @return SdtArchive
     * @throws Exception
     */
    public function build(Sdt $entity, SdtArchive $archive): SdtArchive
    {
        $archive->setReportDate($entity->getReportDate());
        $archive->setUser($entity->getUser());
        $archive->setCount($entity->getCount());
        $createDate = $entity->getCreateDate();
        if ($createDate && $createDate instanceof DateTime) {
            $archive->setCreateDate(DateTimeImmutable::createFromMutable($createDate));
        } else {
            $archive->setCreateDate(new DateTimeImmutable());
        }
        $archive->setActing($entity->getActing());
        return $archive;
    }
}
