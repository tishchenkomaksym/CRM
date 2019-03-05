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
        return (new \Swift_Message($this->mailData->getSubject()))
            ->setFrom($this->mailData->getFromEmail())
            ->setTo($this->mailData->getToEmails())
            ->setBody(
                $this->templating->render(
                    'emails/sdt/newSdt.twig',
                    [
                        'fromDate' => $this->mailData->getFromDate(),
                        'toDate' => $this->mailData->getToDate(),
                        'daysCount' => $this->mailData->getDaysCount(),
                        'actingPeople' => $this->mailData->getActingPeople(),
                    ]
                ),
                'text/html'
            );
    }
}
