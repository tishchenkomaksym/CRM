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
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

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
     * @return string
     * @throws NoDateException
     */
    public function build():string
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
        try {
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
        } catch (Twig_Error_Loader $e) {
        } catch (Twig_Error_Runtime $e) {
        } catch (Twig_Error_Syntax $e) {
        }
    }
}
