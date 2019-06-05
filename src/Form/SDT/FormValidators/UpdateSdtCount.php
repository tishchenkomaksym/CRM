<?php


namespace App\Form\SDT\FormValidators;


use App\Entity\Sdt;
use Symfony\Component\Validator\Constraint;

class UpdateSdtCount extends Constraint
{
    public $message = 'You don\'t have enough SDT.';
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
     * @return UpdateSdtCount
     */
    public function setOldSdtEntity(Sdt $oldSdtEntity): UpdateSdtCount
    {
        $this->oldSdtEntity = $oldSdtEntity;
        return $this;
    }
}