<?php


namespace App\Service\Vacancy\CreateForAliasDepartment;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Vacancy;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateForAliasDepartment
{
//    private $templating;
//
//    private $vacancy;
//
//    public function __construct(Vacancy $vacancy, Environment $templating)
//    {
//        $this->vacancy = $vacancy;
//
//        $this->templating = $templating;
//
////        $vacancy->getDepartment()->getId()
//    }
//
//    /**
//     * @return Swift_Message
//     * @throws NoDateException
//     * @throws LoaderError
//     * @throws RuntimeError
//     * @throws SyntaxError
//     */
//
//    public function build(): Swift_Message
//    {
////        $email = $this->vacancy->getDepartment()->getEmail(); // 1 вариант
//
////        $emails = $this->aliasRepository->findAll(); //2 вариант
////        foreach($emails as $email){
////            if($email['id'] === $this->vacancy->getDepartment()->getId) {
////                $em = $email['name];
////            }
////        }
//
//        if (isset($emails)){
//            $result = (new Swift_Message('Hiring request denied'))
//                ->setFrom(getenv('LOCAL_EMAIL'))
//                ->setTo($emails)
//                ->setBody(
//                    $this->templating->render(
//                        'emails/vacancy/newVacancyForHrManager.twig',
//                        [
//                            'vacancy' => $this->vacancy,
//                        ]
//                    ),
//                    'text/html'
//                );
//        }else {
//            throw new NoDateException('Wrong configuration');
//        }
//        return $result;
//    }
}