<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 17:55
 */

namespace App\Command;

use App\Data\Sdt\Mail\Adapter\NewSdtMailFromSdtAdapter;
use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Repository\SdtRepository;
use App\Repository\UserInfoRepository;
use App\Service\HolidayService;
use App\Service\Sdt\Create\NewSDTMessageBuilder;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class SendMailCommand extends Command
{
    protected static $defaultName = 'app:sdt-send-mail';
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var HolidayService
     */
    private $holidayService;
    /**
     * @var UserInfoRepository
     */
    private $userInfoRepository;
    /**
     * @var SdtRepository
     */
    private $sdtRepository;
    /**
     * @var Twig_Environment
     */
    private $environment;
    /**
     * @var NewSdtMailFromSdtAdapter
     */
    private $newSdtMailFromSdtAdapter;

    /**
     * TestMailCommand constructor.
     * @param Swift_Mailer $mailer
     * @param HolidayService $holidayService
     * @param UserInfoRepository $userInfoRepository
     * @param SdtRepository $sdtRepository
     * @param Twig_Environment $environment
     * @param NewSdtMailFromSdtAdapter $newSdtMailFromSdtAdapter
     * @param null|string $name
     */
    public function __construct(
        Swift_Mailer $mailer,
        HolidayService $holidayService,
        UserInfoRepository $userInfoRepository,
        SdtRepository $sdtRepository,
        Twig_Environment $environment,
        NewSdtMailFromSdtAdapter $newSdtMailFromSdtAdapter,
        ?string $name = null
    ) {
        parent::__construct($name);
        $this->mailer = $mailer;
        $this->holidayService = $holidayService;
        $this->userInfoRepository = $userInfoRepository;
        $this->sdtRepository = $sdtRepository;
        $this->environment = $environment;
        $this->newSdtMailFromSdtAdapter = $newSdtMailFromSdtAdapter;
    }

    protected function configure()
    {
        $this
            // ...
            ->addArgument('sdtId', InputArgument::REQUIRED, 'Sdt id');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws NoDateException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $sdtId = $input->getArgument('sdtId');
        if ($sdtId) {
            $sdt = $this->sdtRepository->find($sdtId);
            if ($sdt) {
                $sendMail = $this->newSdtMailFromSdtAdapter->getNewSdtMail($sdt, $this->holidayService, $this->userInfoRepository);
                $messageBuilder = new NewSDTMessageBuilder(
                    $sendMail, $this->environment
                );
                echo $this->mailer->send($messageBuilder->build());
            } else {
                echo 'cant find sdt id';
            }
        } else {
            echo 'cant find sdt param';
        }
    }
}
