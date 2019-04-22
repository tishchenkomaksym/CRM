<?php


namespace App\Service\Vacancy\CreateForHrManager;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Repository\UserRepository;
use Swift_Message;
use Twig\Environment;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class NewVacancyMessageBuilderForHrManager
{
    private $templating;

    private $vacancy;

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
     * @throws NoDateException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function build():string
    {
        $users = $this->userRepository->findAll();

        foreach($users as $user){
            if(in_array('ROLE_HR', $user->getRoles(),true)) {
                $emails[] = $user->getEmail();
            }
        }

        if (isset($emails)){
            $result = (new Swift_Message('Hiring request denied'))
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
        }else {
            throw new NoDateException('Wrong configuration');
        }
        return $result;
    }
}