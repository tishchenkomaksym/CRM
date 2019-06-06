<?php


namespace App\Service\Sdt\Create\FormValidators;

use App\Entity\Sdt;
use Symfony\Component\Validator\Constraint;

class SdtPeriod extends Constraint
{
    public $message = 'Sorry, it seems you have already created SDT for selected period. Please, edit or delete previously created SDT';
    /**
     * @var Sdt
     */
    private $oldSdtEntity;

    public function __construct(Sdt $oldSdtEntity)
    {
        parent::__construct([]);

        $this->oldSdtEntity = $oldSdtEntity;
    }

    /**
     * @return Sdt
     */
    public function getOldSdtEntity(): Sdt
    {
        return $this->oldSdtEntity;
    }

    /**
     * @param Sdt $oldSdtEntity
     * @return SdtPeriod
     */
    public function setOldSdtEntity(Sdt $oldSdtEntity): SdtPeriod
    {
        $this->oldSdtEntity = $oldSdtEntity;
        return $this;
    }
}