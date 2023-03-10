<?php


namespace App\Command\CandidateVacancyLetters;


use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkProvider;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkVacancyInterface;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateVacancyProvider;
use App\Service\Vacancy\Letters\CreateForDepartmentManagerAfterInterview\CreateForDepartmentManagerAfterInterview;
use Swift_Mailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class LetterAfterInterviewConsoleCommand extends Command
{
    protected static $defaultName = 'app:mail-after-date-interview';
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
        if (!empty($this->candidateVacancies->letterAfterInterview())) {
            foreach ($this->candidateVacancies->letterAfterInterview() as $candidateVacancy) {
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
        if (!empty($this->candidateLinks->letterAfterInterview())) {
            foreach ($this->candidateLinks->letterAfterInterview() as $candidateLink) {
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
        $messageBuilder = new CreateForDepartmentManagerAfterInterview($this->environment);
        $this->mailer->send($messageBuilder->build($candidateLinkVacancyProvider));
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
        $this->assignDateLettersCandidateLinkRemind();
        $this->assignDateLettersCandidateVacancyRemind();
    }
}