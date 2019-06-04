<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 17:55
 */

namespace App\Command\SDT;

use App\Entity\User;
use App\Repository\SalaryReportInfoRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Service\User\Notification\NoEnoughTimeMessageBuilder;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
use DateTime;
use Exception;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NoEnoughWorkingHours extends Command
{
    protected static $defaultName = 'app:no-enough-working-hours';
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var ReportWorkHoursBuilderDecorator
     */
    private $baseWorkHoursInformationBuilder;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var NoEnoughTimeMessageBuilder
     */
    private $enoughTimeMessageBuilder;
    /**
     * @var SalaryReportInfoRepository
     */
    private $infoRepository;
    /**
     * @var UserInfoRepository
     */
    private $userInfoRepository;

    /**
     * TestMailCommand constructor.
     * @param Swift_Mailer $mailer
     * @param ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
     * @param UserRepository $userRepository
     * @param NoEnoughTimeMessageBuilder $enoughTimeMessageBuilder
     * @param SalaryReportInfoRepository $infoRepository
     * @param UserInfoRepository $userInfoRepository
     * @param string|null $name
     */
    public function __construct(
        Swift_Mailer $mailer,
        ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder,
        UserRepository $userRepository,
        NoEnoughTimeMessageBuilder $enoughTimeMessageBuilder,
        SalaryReportInfoRepository $infoRepository,
        UserInfoRepository $userInfoRepository,
        ?string $name = null
    ) {
        parent::__construct($name);
        $this->mailer = $mailer;
        $this->baseWorkHoursInformationBuilder = $baseWorkHoursInformationBuilder;
        $this->userRepository = $userRepository;
        $this->enoughTimeMessageBuilder = $enoughTimeMessageBuilder;
        $this->infoRepository = $infoRepository;
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws Exception
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $users = $this->userRepository->findBy(
            []
//            ['email'=>'oleksandra.bi@onyx.com']
        );

        $nowDate = new DateTime();
        $nextSalaryReport = $this->infoRepository->getNextSalaryReport($nowDate);
        $dayOfWeek = $nowDate->format('w');
        if ($nextSalaryReport !== null && ($dayOfWeek === '1' || $dayOfWeek === '4')) {
            foreach ($users as $user) {
                if ($this->isAllowedToSend($user)) {
                    $userHoursInfo = $this->baseWorkHoursInformationBuilder->build($user, $nowDate);
                    $timeDiff = $userHoursInfo->getRequiredTime() - $userHoursInfo->getLoggedTime();
                    if ($timeDiff > 0) {
                        $message = $this->enoughTimeMessageBuilder->build(
                            $timeDiff,
                            $nowDate,
                            $this->baseWorkHoursInformationBuilder->getSalaryReportDate(),
                            $nextSalaryReport->getCreateDate(),
                            'oleksandra.bi@onyx.com'
//                        $user->getEmail()
                        );
                        $this->mailer->send($message);
                    }
                }
            }
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    private function isAllowedToSend(User $user): bool
    {
        if ($user->getTeam() === null || ($user->getTeam()->getDepartment() !== null && $user->getTeam()->getDepartment()->getName() !== 'Development team')) {
            return false;
        }
        $userInfoObject = $this->userInfoRepository->findOneBy(['user' => $user]);
        return !($userInfoObject !== null && $userInfoObject->getSubTeam() === 'Central Tech Support');
    }
}
