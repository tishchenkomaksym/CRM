<?php
/**
 * Created by PhpStorm.
 * User: ivan.me
 * Date: 26.02.2019
 * Time: 15:38
 */

namespace App\Service\Vacancy\CreateVacancy;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use App\Service\Sdt\MessageBuilderInterface;
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
     * @throws NoDateException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(): Swift_Message
    {
        $office = $this->vacancy->getOffice();
        if (
            $office === null ||
            $office->getTopManager() === null ||
            $office->getTopManager()->getEmail() === null
        ) {
            throw new NoDateException('Wrong configuration of top manager');

        }
        $email = $office->getTopManager()->getEmail();
        $object = new Swift_Message('Hiring request approval');
        $object
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
        return $object;
    }
}
