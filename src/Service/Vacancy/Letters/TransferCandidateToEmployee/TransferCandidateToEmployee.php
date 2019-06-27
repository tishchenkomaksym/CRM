<?php


namespace App\Service\Vacancy\Letters\TransferCandidateToEmployee;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\EmployeeOnBoardingInfo;
use App\Service\Sdt\MessageBuilderInterface;
use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TransferCandidateToEmployee implements MessageBuilderInterface
{
    private $templating;
    /**
     * @var EmployeeOnBoardingInfo
     */
    private $employeeOnBoardingInfo;
    private $builder;


    public function __construct(Environment $templating,
                                EmployeeOnBoardingInfo $employeeOnBoardingInfo, $builder)
    {
        $this->templating = $templating;
        $this->employeeOnBoardingInfo = $employeeOnBoardingInfo;
        $this->builder = $builder;
    }

    /**
     * @return Swift_Message
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function build(): Swift_Message
    {
        $email = ['accounts.manager@onyx.com', 'helpdesk@onyx.com'];
        $object = new Swift_Message('New employee access to user-profile.devzone.net ||' . $this->employeeOnBoardingInfo->getFullName());
        $object
            ->setFrom(getenv('LOCAL_EMAIL'))
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'emails/vacancy/transferCandidateToEmployee.html.twig',
                    [
                        'employeeInfo' => $this->employeeOnBoardingInfo,
                        'builder' => $this->builder
                    ]
                ),
                'text/html'
            );
        return $object;
    }
}