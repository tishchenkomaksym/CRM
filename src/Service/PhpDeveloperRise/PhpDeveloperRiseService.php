<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 11.02.2019
 * Time: 11:50
 */

namespace App\Service\PhpDeveloperRise;

use App\Entity\PhpDeveloperRiseRequest;
use App\Service\PhpDeveloperRise\Exception\WrongPhpDeveloperConfiguration;

class PhpDeveloperRiseService
{
    /**
     * @param PhpDeveloperRiseRequest $riseRequest
     * @return \App\Entity\User|null
     * @throws WrongPhpDeveloperConfiguration
     */
    public static function processRiseUp(PhpDeveloperRiseRequest $riseRequest): ?\App\Entity\User
    {
        try {
            $phpDeveloper = $riseRequest->getPhpDeveloper();
            if ($phpDeveloper) {
                $nextLevel = $phpDeveloper->getPhpDeveloperLevelRelation()->getPhpDeveloperLevel()->getNextLevel();
                if ($nextLevel) {
                    $phpDeveloper->getPhpDeveloperLevelRelation()->setPhpDeveloperLevel($nextLevel);
                    return $phpDeveloper;
                }
            }
            throw new WrongPhpDeveloperConfiguration('Have wrong developer configuration');
        } catch (\Exception $exception) {
            throw new WrongPhpDeveloperConfiguration('Have wrong developer configuration');
        }
    }
}
