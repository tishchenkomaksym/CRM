<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 14:18
 */

namespace App\Service\Sdt\Create;

use App\Entity\Sdt;
use App\Service\Sdt\Exception\EmailServerNotWorking;

class BaseCreateContext
{
    /** @var BaseCreateStrategy */
    private $strategy;

    /**
     * BaseCreateContextVacancy constructor.
     * @param BaseCreateStrategy $strategy
     */
    public function __construct(BaseCreateStrategy $strategy)
    {
        $this->strategy = $strategy;
    }


    /**
     * @return Sdt
     * @throws EmailServerNotWorking
     * @throws \Exception
     */
    public function createSdt(): Sdt
    {
        if ($this->strategy->sendEmail() === 0) {
            throw  new EmailServerNotWorking(EmailServerNotWorking::MESSAGE);
        }
        return $this->strategy->create();
    }
}
