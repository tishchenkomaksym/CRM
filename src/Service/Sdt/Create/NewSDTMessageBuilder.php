<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 15:38
 */

namespace App\Service\Sdt\Create;

use App\Data\Sdt\Mail\NewSdtMailData;
use App\Service\Sdt\MessageBuilderInterface;
use Twig_Environment;

class NewSDTMessageBuilder implements MessageBuilderInterface
{
    private $templating;

    /**
     * @var NewSdtMailData
     */
    private $mailData;


    public function __construct(NewSdtMailData $mailData, Twig_Environment $templating)
    {
        $this->mailData = $mailData;
        $this->templating = $templating;
    }

    /**
     * @return \Swift_Message
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function build()
    {
        $mailData = $this->mailData;
        $message = (new \Swift_Message($mailData->getSubject()))
            ->setFrom($mailData->getFromEmail())
            ->setTo($mailData->getToEmails())
            ->setBody(
                $this->templating->render(
                    'emails/sdt/newSdt.twig',
                    [
                        'fromDate' => $mailData->getFromDate(),
                        'toDate' => $mailData->getToDate(),
                        'daysCount' => $mailData->getDaysCount(),
                        'actingPeople' => $mailData->getActingPeople(),
                    ]
                ),
                'text/html'
            );
        return $message;
    }
}
