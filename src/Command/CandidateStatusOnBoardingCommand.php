<?php


namespace App\Command;

use App\Repository\CandidateLinkRepository;
use App\Repository\CandidateVacancyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CandidateStatusOnBoardingCommand extends Command
{
    protected static $defaultName = 'app:candidate-status-new-employee-on-boarding';
    /**
     * @var CandidateVacancyRepository
     */
    private $candidateVacancies;
    /**
     * @var CandidateLinkRepository
     */
    private $candidateLinks;
    /**
     * @var ObjectManager
     */
    private $entityManager;


    public function __construct(
        CandidateVacancyRepository $candidateVacancyRepository,
        CandidateLinkRepository $candidateLinkRepository,
        ObjectManager $entityManager) {

        parent::__construct();

        $this->candidateVacancies = $candidateVacancyRepository;
        $this->candidateLinks = $candidateLinkRepository;
        $this->entityManager = $entityManager;
    }



    public function changeStatusCandidateVacancy(): void
    {
        if (!empty($this->candidateVacancies->changeCandidateStatus())) {
            foreach ($this->candidateVacancies->changeCandidateStatus() as $candidateVacancy) {
                $candidateVacancy->setCandidateStatus('New employee onboarding');
                $this->entityManager->persist($candidateVacancy);
            }
            $this->entityManager->flush();
        }
    }


    /**
     *
     */
    public function changeStatusCandidateLink(): void
    {
        if (!empty($this->candidateLinks->changeCandidateStatus())) {
            foreach ($this->candidateLinks->changeCandidateStatus() as $candidateLink) {
                $candidateLink->setCandidateStatus('New employee onboarding');
                $this->entityManager->persist($candidateLink);
            }
                $this->entityManager->flush();
            }

    }


    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Learn Date.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command change candidate status.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->changeStatusCandidateVacancy();
        $this->changeStatusCandidateLink();
    }
}