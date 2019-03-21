<?php
/**
 * Created by PhpStorm.
 * User: rubay
 * Date: 3/20/2019
 * Time: 5:33 PM
 */

namespace App\Service\SalaryReport;


use App\Entity\User;

class SalaryReportDTO
{
    public $calendarWorkingDays;
    public $sdtCount;
    public $salarySize;
    /** @var User */
    public $user;
}