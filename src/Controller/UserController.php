<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\UserProfile\UserCreateEditType;
use App\Form\UserProfile\UserCreateType;
use App\Repository\SalaryReportInfoRepository;
use App\Repository\SDTEmailAssigneeRepository;
use App\Repository\SdtRepository;
use App\Repository\UserInfoRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\SalaryReport\Builder\BaseSalaryReportBuilder;
use App\Service\User\Builder\RegistrationUserBuilder;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
use App\Service\User\Sdt\LeftSdtCalculator;
use App\Service\User\Sdt\UsedSdtDaysCalculator;
use App\Service\UserInformationService;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    public const ROUTE_USER_SHOW = 'user_show';

    public const ROUTE_USER_INDEX = 'user_index';

    /**
     * @IsGranted("ROLE_ACCOUNT_MANAGER")
     * @Route("/", name="user_index", methods={"GET"})
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     */
    public function index(UserInfoRepository $userInfoRepository): Response
    {
        return $this->render(
            'user/index.html.twig',
            [
                'users' => $userInfoRepository->findAll(),
            ]
        );
    }


    /**
     * @IsGranted("ROLE_ACCOUNT_MANAGER")
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param RegistrationUserBuilder $userBuilder
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @return Response
     */
    public function new(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        RegistrationUserBuilder $userBuilder,
        CandidatePhotoDecorator $candidatePhotoDecorator
    ): Response {
        $user = new User();
        $userInfo = new UserInfo();
        $form = $this->createForm(UserCreateType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setName($form->get('user')->get('name')->getData());
            $user->setEmail($form->get('user')->get('email')->getData());
            $user->setTeam($form->get('user')->get('team')->getData());
            $user->setPassword($form->get('user')->get('password')->getData());

            $user = RegistrationUserBuilder::build($user, $passwordEncoder, $user->getPassword());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if ($userInfo->getPhoto() !== null) {
                /** @var UploadedFile $file */
                $file = $userInfo->getPhoto();
                $fileName = $candidatePhotoDecorator->upload($file);
                $userInfo->setPhoto($fileName);
            }
            $userInfo->setUser($user);
            $entityManager->persist($userInfo);
            $entityManager->flush();
            foreach ($userBuilder->buildUserEmails($user) as $email) {
                $entityManager->persist($email);
            }
            $entityManager->flush();

            return $this->redirectToRoute(self::ROUTE_USER_INDEX);
        }

        return $this->render(
            'user/new.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     * @param UserInfo $userInfo
     * @param UserInformationService $service
     * @param UsedSdtDaysCalculator $usedSdtDaysCalculator
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
     * @param SalaryReportInfoRepository $reportInfoRepository
     * @param SdtRepository $sdtRepository
     * @return Response
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function show(UserInfo $userInfo,
        UserInformationService $service,
        BaseSalaryReportBuilder $baseSalaryReportBuilder,
        LeftSdtCalculator $leftSdtCalculator,
        ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder,
        SalaryReportInfoRepository $reportInfoRepository,
        SdtRepository $sdtRepository
        ): Response
    {
        $user = $this->getUser();
        $todaySalaryReport = $reportInfoRepository->getTodaySalaryReport();
        $nextSalaryReport = $reportInfoRepository->getNextSalaryReport(new DateTime());
        $sdtUsed = $baseSalaryReportBuilder->build($todaySalaryReport, $user);
        $manager = $service->getPhpDeveloperManager($user);
        $leftSdt = $leftSdtCalculator->calculate($this->getUser());
//        $workingHoursInformation = $baseWorkHoursInformationBuilder->build($this->getUser());
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $userInfo,
                'userLevel' => $service->getPhpUserLevel($user),
                'developerManagers' => $manager,
                'leftSdt' => $leftSdt,
                'todaySalaryReport' => $todaySalaryReport,
                'nextSalaryReport' => $nextSalaryReport,
                'sdtUsed' => $sdtUsed,
//                'workingHoursInformation' => $workingHoursInformation
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ACCOUNT_MANAGER")
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     * @param UserInfo $userInfo
     * @param Request $request
     * @param RegistrationUserBuilder $userBuilder
     * @param SDTEmailAssigneeRepository $assigneeRepository
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @return Response
     * @throws NoDataException
     */
    public function edit(
        UserInfo $userInfo,
        Request $request,
        RegistrationUserBuilder $userBuilder,
        SDTEmailAssigneeRepository $assigneeRepository,
        CandidatePhotoDecorator $candidatePhotoDecorator
    ): Response {
        $photo = $userInfo->getPhoto();
        $candidatePhotoDecorator->photoNotNull($userInfo);

        $form = $this->createForm(UserCreateEditType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $userInfo->getPhoto();
            if ($file !== null) {
                $fileName = $candidatePhotoDecorator->upload($file);
                $userInfo->setPhoto($fileName);
            }else{
                $userInfo->setPhoto($photo);
            }
            $user = $userInfo->getUser();
            if ($user === null){
                throw new NoDataException('User not found');
            }
            $user->setName($form->get('user')->get('name')->getData());
            $user->setEmail($form->get('user')->get('email')->getData());
            $user->setTeam($form->get('user')->get('team')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userInfo);
            $entityManager->persist($user);
            $entityManager->flush();

            foreach ($assigneeRepository->findBy(['user' => $userInfo->getId()]) as $SDTEmailAssignee) {
                $entityManager->remove($SDTEmailAssignee);
            }
            foreach ($userBuilder->buildUserEmails($userInfo->getUser()) as $email) {
                $entityManager->persist($email);
            }
            $entityManager->flush();
            return $this->redirectToRoute(
                self::ROUTE_USER_INDEX,
                [
                    'id' => $userInfo->getId(),
                ]
            );
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'user' => $userInfo,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     * @param Request $request
     * @param UserInfo $userInfo
     * @return Response
     */
    public function delete(Request $request, UserInfo $userInfo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $userInfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userInfo);
            $entityManager->flush();
        }

        return $this->redirectToRoute(self::ROUTE_USER_INDEX);
    }
}
