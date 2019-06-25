<?php


namespace App\Command\CandidateVacancyLetters;


use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Service\CandidateForms\CandidateForms;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkProvider;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateVacancyProvider;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForRecruiter\CreateEmployeeStartDateForRecruiterRemind;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RemindLetterBeforeStartWorkConsoleCommand extends Command
{
    protected static $defaultName = 'app:mail-before-start-date-employee';
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
    /**
     * @var CandidateForms
     */
    private $candidateForms;


    public function __construct(Environment $environment,
        Swift_Mailer $mailer,
        CandidateVacancyRepository $candidateVacancyRepository,
        CandidateLinkRepository $candidateLinkRepository,
        CandidateForms $candidateForms) {

        parent::__construct();

        $this->environment = $environment;
        $this->mailer = $mailer;
        $this->candidateVacancies = $candidateVacancyRepository;
        $this->candidateLinks = $candidateLinkRepository;
        $this->candidateForms = $candidateForms;
    }


    /**
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function startDateRemind(): void
    {
        if (!empty($this->candidateVacancies->letterBeforeTwoDay())) {
            foreach ($this->candidateVacancies->letterBeforeTwoDay() as $candidateVacancy) {
                echo 'Yes message';
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
    public function startDateRemindCandidateLink(): void
    {
        if (!empty($this->candidateLinks->letterBeforeTwoDay())) {
            foreach ($this->candidateLinks->letterBeforeTwoDay() as $candidateLink) {
                echo 'Yes message';
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
        $messageBuilder = new CreateEmployeeStartDateForRecruiterRemind($this->environment);
        $this->mailer->send($messageBuilder->build($candidateLinkVacancyProvider, $this->candidateForms));
    }

    protected function configure():void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Learn Date.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to learn Date.');
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
        $this->startDateRemindCandidateLink();
        $this->startDateRemind();
    }
}