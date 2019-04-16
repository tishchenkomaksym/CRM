<?php


namespace App\Service\Vacancy\CreateForHrManager;


use App\Entity\User;
use App\Entity\Vacancy;
use App\Repository\UserRepository;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NewVacancyMessageBuilderForHrManager
{
    private $templating;

    private $vacancy;

    private $user;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, Vacancy $vacancy, Environment $templating)
    {
        $this->vacancy = $vacancy;

        $this->templating = $templating;
        $this->userRepository = $userRepository;
    }

    /**
     * @return Swift_Message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \Exception
     */
    public function build()
    {
        $users = $this->userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_HR', $user->getRoles())) {
                $emails[] = $user->getEmail();
            }
        }

        return (new Swift_Message('Hiring request denied'))
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($emails)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/newVacancyForHrManager.twig',
                    [
                        'vacancy' => $this->vacancy,
                    ]
                ),
                'text/html'
            );
    }
}