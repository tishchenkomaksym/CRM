<?php


namespace App\Service\Vacancy\CreateForManager;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Service\Sdt\MessageBuilderInterface;
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
     * @return string
     * @throws LoaderError
     * @throws NoDateException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build():string
    {

        if (
            $this->vacancy === null ||
            $this->vacancy->getCreatedBy() === null ||
            $this->vacancy->getCreatedBy()->getEmail() === null
        ) {
            throw new NoDateException('Wrong configuration of top manager');

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