<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 17:55
 */

namespace App\Command;

use App\Data\Sdt\Mail\BaseSdtMailData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestMailCommand extends Command
{
    protected static $defaultName = 'app:test-mail';
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * TestMailCommand constructor.
     * @param null|string $name
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer, ?string $name = null)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailData = New BaseSdtMailData('ivan');
        $this->mailer->send(
            $message = (new \Swift_Message('test message'))
                ->setFrom($mailData->getFromEmail())
                ->setTo('ivan.melnichuk@onyx.com')
                ->setBody(
                    'qwe',
                    'text/html'
                )
        );
    }
}
