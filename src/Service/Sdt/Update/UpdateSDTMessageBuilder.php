<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 15:38
 */

namespace App\Service\Sdt\Update;

use App\Data\Sdt\Mail\EditSdtMailData;
use App\Service\Sdt\MessageBuilderInterface;
use Twig_Environment;

class UpdateSDTMessageBuilder implements MessageBuilderInterface
{
    private $templating;

    /**
     * @var EditSdtMailData
     */
    private $mailData;


    public function __construct(EditSdtMailData $mailData, Twig_Environment $templating)
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
                    'emails/sdt/editSdt.twig',
                    [
                        'oldFromDate' => $mailData->getOldFromDate(),
                        'oldToDate' => $mailData->getOldToDate(),
                        'newFromDate' => $mailData->getOldFromDate(),
                        'newToDate' => $mailData->getNewToDate(),
                        'actingPeople' => $mailData->getActingPeople(),
                        'daysCount' => $mailData->getDaysCount(),
                    ]
                ),
                'text/html'
            );
        return $message;
    }
}
