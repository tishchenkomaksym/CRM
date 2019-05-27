<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 14:18
 */

namespace App\Service\Sdt\Update;

use App\Entity\Sdt;
use App\Service\Sdt\Exception\EmailServerNotWorking;
use Exception;

class BaseUpdateContext
{
    /** @var BaseUpdateStrategy */
    private $strategy;

    /**
     * BaseUpdateContext constructor.
     * @param BaseUpdateStrategy $strategy
     */
    public function __construct(BaseUpdateStrategy $strategy)
    {
        $this->strategy = $strategy;
    }


    /**
     * @return Sdt
     * @throws Exception
     * @throws EmailServerNotWorking
     */
    public function updateSDT(): Sdt
    {
        if ($this->strategy->sendEmail() === 0) {
            throw new EmailServerNotWorking(EmailServerNotWorking::MESSAGE);
        }
        $entity = $this->strategy->update();
        $this->strategy->createArchive();
        return $entity;
    }
}
