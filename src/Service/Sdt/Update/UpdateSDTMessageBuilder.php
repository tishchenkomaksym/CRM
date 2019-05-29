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
use Swift_Message;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

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
     * @return Swift_Message
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function build(): Swift_Message
    {
        $mailDataObject = $this->mailData;
        if ($mailDataObject->isAtOwnExpense()) {
            $templateName = 'emails/sdt/editAtOwnExpenseSdt.twig';
        } else {
            $templateName = 'emails/sdt/editSdt.twig';
        }
        return (new Swift_Message($mailDataObject->getSubject()))
            ->setFrom($mailDataObject->getFromEmail())
            ->setTo($mailDataObject->getToEmails())
            ->setBody(
                $this->templating->render(
                    $templateName,
                    [
                        'oldFromDate' => $mailDataObject->getOldFromDate(),
                        'oldToDate' => $mailDataObject->getOldToDate(),
                        'newFromDate' => $mailDataObject->getNewFromDate(),
                        'newToDate' => $mailDataObject->getNewToDate(),
                        'actingPeople' => $mailDataObject->getActingPeople(),
                        'daysCount' => $mailDataObject->getDaysCount(),
                    ]
                ),
                'text/html'
            );
    }
}
