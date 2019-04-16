<?php


namespace App\Service\Vacancy\CreateForManager;


use App\Entity\Vacancy;
use App\Service\Sdt\MessageBuilderInterface;
use RuntimeException;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;



class NewVacancyMessageBuilderForManager implements MessageBuilderInterface
{
    private $templating;

    private $vacancy;

    public function __construct(Vacancy $vacancy, Environment $templating)
    {
        $this->vacancy = $vacancy;

        $this->templating = $templating;
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



        if (
            $this->vacancy === null ||
            $this->vacancy->getCreatedBy() === null ||
            $this->vacancy->getCreatedBy()->getEmail() === null
        ) {
            throw new RuntimeException('Wrong configuration of top manager');

        }
        $email = $this->vacancy->getCreatedBy()->getEmail();
        return (new Swift_Message('Hiring request'))
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/newVacancyForManager.twig',
                    [
                        'vacancy' => $this->vacancy,
                    ]
                ),
                'text/html'
            );
    }
}