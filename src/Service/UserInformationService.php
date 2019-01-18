<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/18/2019
 * Time: 11:56 AM
 */

namespace App\Service;

use App\Data\Sdt\SdtCollection;
use App\Repository\SdtRepository;

class UserInformationService
{
    /**
     * UserInformationService constructor.
     * @param SdtRepository $sdtRepository
     * @param int $userId
     */
    public function __construct()
    {

    }

    public function getSdtCountSum(SdtCollection $sdtCollection)
    {
        $sum = 0;
        foreach ($sdtCollection->getItems() as $collectionItem) {
            $sum += $collectionItem->getCount();
        }
        return $sum;
    }

    public function getAllUserSdt(SdtRepository $sdtRepository, int $userId): SdtCollection
    {
        return new SdtCollection($sdtRepository->findBy(['userId' => $userId]));
    }
}
