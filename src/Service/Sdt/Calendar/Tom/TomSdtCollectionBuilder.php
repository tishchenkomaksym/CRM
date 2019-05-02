<?php


namespace App\Service\Sdt\Calendar\Tom;


use App\Data\Sdt\SdtCollection;
use App\Repository\SdtRepository;

class TomSdtCollectionBuilder
{
    /**
     * @var SdtRepository
     */
    private $sdtRepository;

    public function __construct(SdtRepository $sdtRepository)
    {
        $this->sdtRepository = $sdtRepository;
    }

    public function buildCollection(): SdtCollection
    {
        return new SdtCollection($this->sdtRepository->findAll());
    }
}