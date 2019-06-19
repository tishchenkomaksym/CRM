<?php

namespace App\Controller;

use App\Data\Sdt\Mail\Adapter\NoDateException;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\RolesType;
use App\Form\UserProfile\UserCreateEditType;
use App\Form\UserProfile\UserCreateType;
use App\Repository\SalaryReportInfoRepository;
use App\Repository\SDTEmailAssigneeRepository;
use App\Repository\UserInfoRepository;
use App\Repository\UserRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\SalaryReport\Builder\BaseSalaryReportBuilder;
use App\Service\User\Builder\RegistrationUserBuilder;
use App\Service\User\PhpDeveloper\Hours\ReportWorkHoursBuilderDecorator;
use App\Service\User\Sdt\LeftSdtCalculator;
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
     * @param UserRepository $userRepository
     * @param UserInfoRepository $userInfoRepository
     * @return Response
     * @throws NoDateException
     */
    public function index(
        UserRepository $userRepository,
        UserInfoRepository $userInfoRepository
    ): Response {

        $users = $userRepository->findAll();
        $userInfos = $userInfoRepository->findAll();
        $usersInformation = [];
        $usersNoInfo = [];
        foreach ($users as $user) {
            foreach ($userInfos as $userInfo) {
                if ($userInfo->getUser() === null) {
                    throw new NoDateException('UserId not found');
                }
                if ($user->getId() === $userInfo->getUser()->getId()) {
                    $usersInformation[$user->getId()] = $userInfo;
                    break;
                }
            }
            if (empty($usersInformation[$user->getId()])) {
                $usersNoInfo[] = $user;
            }
        }

        return $this->render(
            'user/index.html.twig',
            [
                'users' => $usersInformation,
                'userNoInfos' => $usersNoInfo
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
     * @throws Exception
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
     * @param User $user
     * @param UserInfoRepository $userInfoRepository
     * @param UserInformationService $service
     * @param BaseSalaryReportBuilder $baseSalaryReportBuilder
     * @param LeftSdtCalculator $leftSdtCalculator
     * @param ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder
     * @param SalaryReportInfoRepository $reportInfoRepository
     * @return Response
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function show(
        User $user,
        UserInfoRepository $userInfoRepository,
        UserInformationService $service,
        BaseSalaryReportBuilder $baseSalaryReportBuilder,
        LeftSdtCalculator $leftSdtCalculator,
        ReportWorkHoursBuilderDecorator $baseWorkHoursInformationBuilder,
        SalaryReportInfoRepository $reportInfoRepository
    ): Response {

        if ($this->isGranted('ROLE_ACCOUNT_MANAGER')){
            $userInfo = $userInfoRepository->findOneBy(['user' => $user->getId()]);
            if ($userInfo === null) {
                $userInfo = new UserInfo();
                $userInfo->setUser($user);
                $userInfo->setBirthDay(new DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userInfo);
                $entityManager->flush();
            }
            $todaySalaryReport = $reportInfoRepository->getTodaySalaryReport();
            $nextSalaryReport = $reportInfoRepository->getNextSalaryReport(new DateTime());
            if ($nextSalaryReport === null){
                throw new NoDataException('Next salary report not found');
            }
            $sdtUsed = $baseSalaryReportBuilder->build($todaySalaryReport, $nextSalaryReport, $user);
            $manager = $service->getPhpDeveloperManager($user);
            $qaManager = $service->getQaManager($user);
            $leftSdt = $leftSdtCalculator->calculate($user);
            $workingHoursInformation = $baseWorkHoursInformationBuilder->build($user, new DateTime());
        }else{
            $userInfo = $userInfoRepository->findOneBy(['user' => $this->getUser()->getId()]);
            if ($userInfo === null) {
                $userInfo = new UserInfo();
                $userInfo->setUser($user);
                $userInfo->setBirthDay(new DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userInfo);
                $entityManager->flush();
            }
            $todaySalaryReport = $reportInfoRepository->getTodaySalaryReport();
            $nextSalaryReport = $reportInfoRepository->getNextSalaryReport(new DateTime());
            if ($nextSalaryReport === null){
                throw new NoDataException('Next salary report not found');
            }
            $sdtUsed = $baseSalaryReportBuilder->build($todaySalaryReport, $nextSalaryReport, $this->getUser());
            $manager = $service->getPhpDeveloperManager($this->getUser());
            $qaManager = $service->getQaManager($this->getUser());
            $leftSdt = $leftSdtCalculator->calculate($this->getUser());
            $workingHoursInformation = $baseWorkHoursInformationBuilder->build($this->getUser(), new DateTime());
        }


        return $this->render(
            'user/show.html.twig',
            [
                'user' => $userInfo,
                'userLevel' => $service->getPhpUserLevel($user),
                'developerManagers' => $manager,
                'qaUserManagers' => $qaManager,
                'leftSdt' => $leftSdt,
                'todaySalaryReport' => $todaySalaryReport,
                'nextSalaryReport' => $nextSalaryReport,
                'sdtUsed' => $sdtUsed,
                'workingHoursInformation' => $workingHoursInformation
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
        CandidatePhotoDecorator $candidatePhotoDecorator
    ): Response {
        $photo = $userInfo->getPhoto();
        $candidatePhotoDecorator->photoNotNull($userInfo);

        $form = $this->createForm(UserCreateEditType::class, $userInfo);
        if ($userInfo->getUser() === null){
            throw new NoDataException('User not found');
        }
        if ($userInfo->getUser()->getTeam() === null){
            throw new NoDataException('User team not found');
        }
        if ($userInfo->getUser()->getTeam()->getDepartment() === null){
            throw new NoDataException('Department not found');
        }
        $form->get('user')->get('office')->setData($userInfo->getUser()->getTeam()->getDepartment()->getOffice());
        $form->get('user')->get('department')->setData($userInfo->getUser()->getTeam()->getDepartment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $userInfo->getPhoto();
            if ($file !== null) {
                $fileName = $candidatePhotoDecorator->upload($file);
                $userInfo->setPhoto($fileName);
            } else {
                $userInfo->setPhoto($photo);
            }
            $user = $userInfo->getUser();
            if ($user === null) {
                throw new NoDataException('User not found');
            }
            $user->setName($form->get('user')->get('name')->getData());
            $user->setEmail($form->get('user')->get('email')->getData());
            $user->setTeam($form->get('user')->get('team')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userInfo);
            $entityManager->persist($user);
            $entityManager->flush();

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
     * @IsGranted("ROLE_MANAGE_ROLES")
     * @Route("/{id}/roles", name="user_roles")
     * @param User $user
     * @param Request $request
     * @return Response
     */

    public function allRoles(User $user, Request $request): Response
    {
        $form = $this->createForm(RolesType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute(
                'user_show',
                [
                    'id' => $user->getId(),
                ]
            );
        }
        return $this->render('user/roles.html.twig',
            [
                'form' => $form->createView()
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
