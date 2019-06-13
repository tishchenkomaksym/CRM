<?php


namespace App\Service\Vacancy\CreateDateConsoleCommand;

use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkProvider;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateVacancyProvider;
use App\Service\Vacancy\Letters\CreateForDepartmentManagerSetTime\CreateForDepartmentManagerSetTimeRemind;
use App\Service\Vacancy\Letters\CreateForRecruiterSetTime\CreateForRecruiterSetTimeRemind;
use App\Service\Vacancy\Letters\CreateForViewerSetTime\CreateForViewerSetTimeRemind;
use DateTime;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateDateConsoleCommand extends Command
{
    protected static $defaultName = 'app:mail-before-one-day-date-interview';
    /**
     * @var Environment
     */
    private $environment;
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancies;
    /**
     * @var CandidateLinkRepository
     */
    private $candidateLinks;


    public function __construct(Environment $environment,
        Swift_Mailer $mailer,
        CandidateVacancyRepository $candidateVacancyRepository,
        CandidateLinkRepository $candidateLinkRepository) {

        parent::__construct();

        $this->environment = $environment;
        $this->mailer = $mailer;
        $this->candidateVacancies = $candidateVacancyRepository;
        $this->candidateLinks = $candidateLinkRepository;

    }


    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function assignDateLettersCandidateVacancyRemind(): void
    {
        if (!empty($this->candidateVacancies->letterBeforeDay())) {
            foreach ($this->candidateVacancies->letterBeforeDay() as $candidateVacancy) {
                    $candidateVacancyProvider = new CandidateVacancyProvider($candidateVacancy);
                    $this->messageBuilder($candidateVacancyProvider);
            }
        }
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function assignDateLettersCandidateLinkRemind(): void
    {
        if (!empty($this->candidateLinks->letterBeforeDay())) {
            foreach ($this->candidateLinks->letterBeforeDay() as $candidateLink) {
                    $candidateLinkProvider = new CandidateLinkProvider($candidateLink);

                    $this->messageBuilder($candidateLinkProvider);

            }
        }
    }

    /**
     * @param CandidateLinkVacancyInterface $candidateLinkVacancyProvider
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function messageBuilder(CandidateLinkVacancyInterface $candidateLinkVacancyProvider): void
    {
        if ($candidateLinkVacancyProvider->vacancy()->getVacancyViewerUser() !== null) {
            $messageBuilderViewer = new CreateForViewerSetTimeRemind($this->environment);
            $this->mailer->send($messageBuilderViewer->build($candidateLinkVacancyProvider));
        }
        $messageBuilder = new CreateForDepartmentManagerSetTimeRemind($this->environment);
        $messageBuilderRecruiter = new CreateForRecruiterSetTimeRemind($this->environment);
        echo 'YES MESSAGE ';
        $this->mailer->send($messageBuilder->build($candidateLinkVacancyProvider));
        $this->mailer->send($messageBuilderRecruiter->build($candidateLinkVacancyProvider));
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Learn Date.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to learn Date.');
//            ->setName(self::$nowDate);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->assignDateLettersCandidateLinkRemind();
        $this->assignDateLettersCandidateVacancyRemind();
    }
}