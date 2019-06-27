<?php


namespace App\Controller\Recruiting;


use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\Candidate;
use App\Entity\EmployeeOnBoardingInfo;
use App\Entity\UserInfo;
use App\Entity\Vacancy;
use App\Form\Recruiting\CandidateOnBoardingInfoType;
use App\Form\Recruiting\TransferCandidateToEmployeeType;
use App\Repository\CandidateRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Repository\VacancyRepository;
use App\Service\CandidateForms\CandidateForms;
use App\Service\Vacancy\CandidateApprove\DepartmentManagerApproveViewDTOBuilder;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use App\Service\Vacancy\CandidateVacancyAmount\CandidateVacancyAmountCheck;
use App\Service\Vacancy\CandidateVacancyAmount\CandidateVacancyAmountCheckLogic;
use App\Service\Vacancy\Letters\TransferCandidateToEmployee\TransferCandidateToEmployee;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CandidateOnBoardingController extends AbstractController
{
    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const CONSTRAINT = 'constraints';

    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/onboarding/{id}", name="candidate_onboarding", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param Request $request
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @return Response
     * @throws NoDataException
     */
    public function candidateOnBoarding(Candidate $candidate, Request $request,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder): Response
    {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );

        return $this->render('recruiting/vacancy/showRecruiter/candidateOnBoarding/newEmployeeOnBoarding.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $builder->getVacancy(),
                'builder' => $builder
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/onboarding/additional/info/{id}", name="candidate_onboarding_additional_info", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateRepository $candidateRepository
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param CandidateVacancyAmountCheckLogic $amountCheckLogic
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws NoDataException
     * @throws NoDateException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function additionalCandidateInfo(Vacancy $vacancy,
        Request $request,
        CandidateRepository $candidateRepository,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        CandidateVacancyAmountCheckLogic $amountCheckLogic,
        Swift_Mailer $mailer): Response
    {
        $employeeOnBoarding = new EmployeeOnBoardingInfo();
        $candidate = $candidateRepository->findOneBy(['id' =>$request->get('candidate')]);
        if ($candidate === null){
            throw new NoDataException('Candidate not found');
        }
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancy->getId()
        );
        $form = $this->createForm(CandidateOnBoardingInfoType::class, $employeeOnBoarding,
            [self::CONSTRAINT => [new CandidateVacancyAmountCheck($vacancy)]]);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $employeeOnBoarding->setCandidate($candidate);
            if ($builder->getCandidateVacancy() !== null){
                $entityManager->persist($builder->getCandidateVacancy()->setCandidateStatus('Employed'));
            }elseif($builder->getCandidateLink() !== null){
                $entityManager->persist($builder->getCandidateLink()->setCandidateStatus('Employed'));
            }
            $entityManager->persist($employeeOnBoarding);
            $entityManager->flush();
            $amountCheckLogic->changeVacancyStatus($builder->getVacancy());
            $messageBuilder = new TransferCandidateToEmployee($this->environment, $employeeOnBoarding, $builder);
            $mailer->send($messageBuilder->build());

            return $this->redirectToRoute('vacancy_show_candidates', [
                'id' => $vacancy->getId(),
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/candidateOnBoarding/additionalOnBoardingInfo.html.twig',
            [
                'form' => $form->createView(),
               self::VACANCY_ENTITY_IN_VIEW => $vacancy
            ]);
    }

    /**
     * @Route("/vacancy/candidate/employed/choose/user/{id}", name="candidate_employed_choose_user", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param Request $request
     * @param UserInfoRepository $userInfoRepository
     * @param VacancyRepository $vacancyRepository
     * @param UserRepository $userRepository
     * @param CandidateForms $candidateForms
     * @return Response
     * @throws NoDataException
     */
    public function chooseUser(Candidate $candidate, Request $request,
        UserInfoRepository $userInfoRepository,
        VacancyRepository $vacancyRepository,
        UserRepository $userRepository,
        CandidateForms $candidateForms): Response
    {
        $form = $this->createForm(TransferCandidateToEmployeeType::class);
        $form->handleRequest($request);
        $vacancy = $vacancyRepository->findOneBy(['id' => $request->get(self::VACANCY_ENTITY_IN_VIEW)]);
        if ($vacancy === null){
            throw new NoDataException('Vacancy not found');
        }
            $candidateLink = $candidateForms->candidateLinkSearch($vacancy->getVacancyLinks(), $candidate->getId());
            $candidateVacancy = $candidateForms->candidateVacancySearch($vacancy, $candidate->getId());

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail = $form->get('name')->getData();
            $user = $userRepository->findOneBy(['email' => $userEmail]);
            if ($user === null){
                throw new NoDataException('User not found');
            }
            if ($userInfoRepository->findOneBy(['user' => $user->getId()]) !== null){
                $userInfo = $userInfoRepository->findOneBy(['user' => $user->getId()]);
            }else{
                $userInfo = new UserInfo();
            }
            if ($candidate->getEmployeeOnBoardingInfo() === null){
                throw new NoDataException('EmployeeOnBoardingInfo not found');
            }
            $user->setName($candidate->getEmployeeOnBoardingInfo()->getFullName());
            if ($candidateLink !== null){
                if ($candidateLink->getCandidateManagerApproval() === null){
                    throw new NoDataException('CandidateManagerApproval not found');
                }
                $user->setTeam($candidateLink->getCandidateManagerApproval()->getTeam());
                $user->setCreateDate($candidateLink->getDateStartWork());
            }elseif($candidateVacancy !== null){
                if ($candidateVacancy->getCandidateManagerApproval() === null){
                    throw new NoDataException('CandidateManagerApproval not found');
                }
                $user->setTeam($candidateVacancy->getCandidateManagerApproval()->getTeam());
                $user->setCreateDate($candidateVacancy->getDateStartWork());
            }
            $userInfo
                ->setPhoto($candidate->getPhoto())
                ->setUser($user)
                ->setLocation($candidate->getLocation())
                ->setPhone($candidate->getPhone())
                ->setPersonalEmail($candidate->getEmail())
                ->setPosition($vacancy->getPosition())
                ->setSex($candidate->getEmployeeOnBoardingInfo()->getSex())
                ->setBirthDay($candidate->getEmployeeOnBoardingInfo()->getBirthday())
                ->setMaritalStatus($candidate->getEmployeeOnBoardingInfo()->getMaritalStatus())
                ->setChildren($candidate->getEmployeeOnBoardingInfo()->getChildren())
                ->setCandidate($candidate);

            $entityManager->persist($user);
            $entityManager->persist($userInfo);
            $entityManager->flush();

                return $this->redirectToRoute('user_edit', [
                'id' => $userInfo->getId(),
                'account'=> 1
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/candidateEmployed/chooseUser.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}