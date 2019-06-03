<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 15:38
 */

namespace App\Service\User\Notification;

use DateTimeInterface;
use Swift_Message;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class NoEnoughTimeMessageBuilder
{
    private $templating;

    public function __construct(Twig_Environment $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param float $time
     * @param DateTimeInterface $fromDate
     * @param DateTimeInterface $toDate
     * @param DateTimeInterface $salaryReportDate
     * @param string $email
     * @return Swift_Message
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function build(
        float $time,
        DateTimeInterface $fromDate,
        DateTimeInterface $toDate,
        DateTimeInterface $salaryReportDate,
        string $email
    ): Swift_Message {
        return (new Swift_Message('Logged time check'))
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email, $email)
            ->setBody(
                $this->templating->render(
                    'emails/user/time/noEnoughTime.twig',
                    [
                        'time' => $time,
                        'toDate' => $toDate,
                        'fromDate' => $fromDate,
                        'salaryReportDate' => $salaryReportDate
                    ]
                ),
                'text/html'
            );
    }
}
