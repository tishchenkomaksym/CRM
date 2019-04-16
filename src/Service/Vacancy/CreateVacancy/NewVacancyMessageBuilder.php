<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 15:38
 */

namespace App\Service\Vacancy\CreateVacancy;

use App\Entity\Vacancy;
use App\Service\Sdt\MessageBuilderInterface;
use RuntimeException;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class NewVacancyMessageBuilder implements MessageBuilderInterface
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
        $office = $this->vacancy->getOffice();
        if (
            $office === null ||
            $office->getTopManager() === null ||
            $office->getTopManager()->getEmail() === null
        ) {
            throw new RuntimeException('Wrong configuration of top manager');

        }
        $email = $office->getTopManager()->getEmail();
        return (new Swift_Message('Hiring request approval'))
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/newVacancy.twig',
                    [
                        'vacancy' => $this->vacancy
                    ]
                ),
                'text/html'
            );
    }
}
