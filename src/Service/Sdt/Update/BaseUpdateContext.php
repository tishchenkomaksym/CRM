<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 14:18
 */

namespace App\Service\Sdt\Update;

use App\Entity\Sdt;

class BaseUpdateContext
{
    /** @var BaseUpdateStrategy */
    private $strategy;

    /**
     * @return mixed
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param mixed $strategy
     */
    public function setStrategy($strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * @return Sdt
     * @throws \Exception
     */
    public function updateSDT(): Sdt
    {
        $entity = $this->strategy->update();
        $this->strategy->sendEmail();
        $this->strategy->createArchive();
        return $entity;
    }
}
