<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 05.03.2019
 * Time: 12:39
 */

namespace App\Service\Sdt\Exception;


use Exception;

class EmailServerNotWorking extends Exception
{
    public const MESSAGE='Email service doesn\'t work. Ask admin';

}
