<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 12.03.2019
 * Time: 14:49
 */

namespace App\Service\User\PhpDeveloperLevel\EffectiveTime;

/**
 * Class NoRequiredHoursException
 * @package App\Service\User\PhpDeveloperLevel\EffectiveTime
 */
class NoRequiredHoursException extends \Exception
{
    public const  MESSAGE='No required hours';
}
