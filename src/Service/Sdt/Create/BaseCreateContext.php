<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 14:18
 */

namespace App\Service\Sdt\Create;

use App\Entity\Sdt;

class BaseCreateContext
{
    /** @var BaseCreateStrategy */
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
    public function createSdt(): Sdt
    {
        $this->strategy->sendEmail();
        return $this->strategy->create();
    }
}
