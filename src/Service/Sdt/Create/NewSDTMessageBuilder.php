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
use Swift_Message;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

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
     * @return Swift_Message
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function build()
    {
        if ($this->mailData->isAtOwnExpensive()) {
            $template = 'emails/sdt/newAtOwnExpenseSdt.twig';
        } else {
            $template = 'emails/sdt/newSdt.twig';
        }
        return (new Swift_Message($this->mailData->getSubject()))
            ->setFrom($this->mailData->getFromEmail())
            ->setTo($this->mailData->getToEmails())
            ->setBody(
                $this->templating->render(
                    $template,
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
