<?php


namespace App\Controller\Recruiting;


use App\Entity\Candidate;
use App\Entity\CandidateManagerApproval;
use App\Entity\CandidateManagerDeny;
use App\Entity\CandidateOfferDeny;
use App\Entity\Vacancy;
use App\Form\Recruiting\ApproveAfterInterviewByDepartmentManagerType;
use App\Form\Recruiting\AssignStartDateEmployeeType;
use App\Form\Recruiting\CandidateContractConcludingType;
use App\Form\Recruiting\CandidateDeclinePropositionType;
use App\Form\Recruiting\DenyAfterInterviewByCandidateType;
use App\Form\Recruiting\DenyAfterInterviewByDepartmentManagerType;
use App\Form\Recruiting\DenyAfterInterviewNotSuitableSalaryType;
use App\Form\Recruiting\RecruiterReportedType;
use App\Service\CandidateForms\CandidateForms;
use App\Service\Vacancy\CandidateApprove\DepartmentManagerApproveViewDTOBuilder;
use App\Service\Vacancy\CandidateApproveAfterInterview\CreateNewDenyReasonCandidate;
use App\Service\Vacancy\CandidateApproveAfterInterview\CreateNewDenyReasonDepartment;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use App\Service\Vacancy\CandidateApproveAfterInterview\FormValidatorsAfterInterview\CandidateManagerApprovalCheckExistence;
use App\Service\Vacancy\CandidateApproveAfterInterview\FormValidatorsAfterInterview\CandidateManagerApprovalExistenceLogic;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkProvider;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateVacancyProvider;
use App\Service\Vacancy\Letters\CreateAfterInterviewApprove\CreateAfterInterviewApprove;
use App\Service\Vacancy\Letters\CreateAfterInterviewApproveAcceptCandidate\CreateAfterInterviewApproveAcceptCandidate;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForManager\CreateEmployeeStartDateForManager;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForManager\CreateEmployeeStartDateForManagerEdit;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForRecruiter\CreateEmployeeStartDateForRecruiter;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForRecruiter\CreateEmployeeStartDateForRecruiterEdit;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForViewer\CreateEmployeeStartDateForViewer;
use App\Service\Vacancy\Letters\CreateEmployeeStartDateForViewer\CreateEmployeeStartDateForViewerEdit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CandidateApproveAfterInterviewController extends AbstractController
{
    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const CONSTRAINT = 'constraints';

    public const BUILDER = 'builder';

    public const VACANCY_SHOW_CANDIDATES = 'vacancy_show_candidates';

    public const CHOICES = 'choices';

    public const CANDIDATE = 'candidate';

    public const CANDIDATE_VACANCY = 'candidateVacancy';

    public const START_DATE_EMPLOYEE  = 'startDateEmployee';

    public const CANDIDATE_LINK = 'candidateLink';

    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }
    /**
     * @IsGranted("ROLE_RECRUITING_DEPARTMENT_MANAGER")
     * @Route("/vacancy/candidate/after/interview/{id}", name="candidate_after_interview", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param CandidateManagerApprovalExistenceLogic $managerApprovalExistenceLogic
     * @param Request $request
     * @return Response
     * @throws NoDataException
     */
    public function approveViewDepartmentManager(
        Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        CandidateManagerApprovalExistenceLogic $managerApprovalExistenceLogic,
        Request $request
    ): Response {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );
        if ($builder->getCandidateVacancy() !== null){
            if(!empty($managerApprovalExistenceLogic->existence($builder->getCandidateVacancy()))){
                throw new NoDataException('This candidate was already approved or denied');
            }
        }elseif(!empty($managerApprovalExistenceLogic->existenceCandidateLink($builder->getCandidateLink()))){
            throw new NoDataException('This candidate was already approved or denied');
        }

        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerAfterInterview/candidateAfterInterview.html.twig',
            [
                self::BUILDER => $builder
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITING_DEPARTMENT_MANAGER")
     * @Route("/vacancy/candidate/after/interview/approve/{id}", name="candidate_after_interview_approve", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @return Response
     * @throws NoDataException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function approveAfterInterview(Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        Swift_Mailer $mailer,
        Request $request): Response
    {

        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );
        $candidateManagerApproval = new CandidateManagerApproval();
        $form = $this->createForm(ApproveAfterInterviewByDepartmentManagerType::class, $candidateManagerApproval,
            [self::CONSTRAINT => [new CandidateManagerApprovalCheckExistence($builder->getCandidateLink(), $builder->getCandidateVacancy())]]);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {


            if ($builder->getCandidateVacancy() !== null) {
                $entityManager->persist($builder->getCandidateVacancy()->setCandidateStatus('Interview'));
                $entityManager->persist($candidateManagerApproval->setCandidateVacancy($builder->getCandidateVacancy()));
            }
            if ($builder->getCandidateLink() !== null) {
                $entityManager->persist($builder->getCandidateLink()->setCandidateStatus('Interview'));
                $entityManager->persist($candidateManagerApproval->setCandidateLink($builder->getCandidateLink()));
            }
            $messageBuilderViewer = new CreateAfterInterviewApprove($this->environment);
            $mailer->send($messageBuilderViewer->build($candidateManagerApproval));
            $entityManager->persist($candidateManagerApproval);
            $entityManager->flush();
            return $this->redirectToRoute('candidate_after_interview_approved_notice', [
                'id' => $builder->getVacancy()->getId()
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerAfterInterview/approveCandidateAfterInterview.html.twig',
            [
                self::BUILDER => $builder,
                'form' => $form->createView()
            ]);
    }


    /**
     * @IsGranted("ROLE_RECRUITING_DEPARTMENT_MANAGER")
     * @Route("/vacancy/candidate/after/interview/approved/notice/{id}", name="candidate_after_interview_approved_notice", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @return Response
     */
    public function approvedNotice(Vacancy $vacancy): Response
    {
        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerAfterInterview/approvedNotice.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy,
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITING_DEPARTMENT_MANAGER")
     * @Route("/vacancy/candidate/after/interview/deny/{id}", name="candidate_after_interview_deny", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param CreateNewDenyReasonDepartment $createNewDenyReasonDepartment
     * @param Request $request
     * @return Response
     * @throws NoDataException
     */
    public function denyAfterInterview(Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        CreateNewDenyReasonDepartment $createNewDenyReasonDepartment,
        Request $request): Response
    {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );

        $candidateManagerDeny = new CandidateManagerDeny();
        $form = $this->createForm(DenyAfterInterviewByDepartmentManagerType::class, $candidateManagerDeny,
            [self::CONSTRAINT => [new CandidateManagerApprovalCheckExistence($builder->getCandidateLink(), $builder->getCandidateVacancy())]]);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $createNewDenyReasonDepartment->newDenyReasonDepartment($form->get(self::CHOICES)->getData(),$candidateManagerDeny);
            if ($builder->getCandidateVacancy() !== null) {
                $entityManager->persist($builder->getCandidateVacancy()->setCandidateStatus('Closed by department manager'));
                $entityManager->persist($candidateManagerDeny->setCandidateVacancy($builder->getCandidateVacancy()));
            }
            if ($builder->getCandidateLink() !== null) {
                $entityManager->persist($builder->getCandidateLink()->setCandidateStatus('Closed by department manager'));
                $entityManager->persist($candidateManagerDeny->setCandidateLink($builder->getCandidateLink()));
            }

            $entityManager->persist($candidateManagerDeny);
            $entityManager->flush();
            return $this->redirectToRoute('candidate_after_interview_deny_notice', [
                'id' => $builder->getVacancy()->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerAfterInterview/denyCandidateAfterInterview.html.twig',
            [
                self::BUILDER => $builder,
                'form' => $form->createView(),
            ]);
    }

        /**
         * @IsGranted("ROLE_RECRUITING_DEPARTMENT_MANAGER")
         * @Route("/vacancy/candidate/after/interview/deny/notice/{id}", name="candidate_after_interview_deny_notice", methods={"GET","POST"})
         * @param Vacancy $vacancy
         * @return Response
         */
        public function denyNotice(Vacancy $vacancy): Response
        {
            return $this->render('recruiting/vacancy/showRecruiter/departmentManagerAfterInterview/denyNotice.html.twig',
                [
                    self::VACANCY_ENTITY_IN_VIEW => $vacancy,
                ]);
        }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/recruiter/{id}", name="candidate_after_interview_view_recruiter", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws LoaderError
     * @throws NoDataException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function approveViewRecruiter(
        Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        Request $request,
        Swift_Mailer $mailer
    ): Response {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );


        $form = $this->createForm(CandidateContractConcludingType::class);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $messageBuilderViewer = new CreateAfterInterviewApproveAcceptCandidate($this->environment);
            if ($builder->getCandidateVacancy() !== null) {
                $entityManager->persist($builder->getCandidateVacancy()->setCandidateStatus('Contract Concluding'));
                $mailer->send($messageBuilderViewer->build($builder->getCandidateVacancy()->getCandidateManagerApproval()));
            }
            if ($builder->getCandidateLink() !== null) {
                $mailer->send($messageBuilderViewer->build($builder->getCandidateLink()->getCandidateManagerApproval()));
                $entityManager->persist($builder->getCandidateLink()->setCandidateStatus('Contract Concluding'));
            }
            $entityManager->flush();

            return $this->redirectToRoute(self::VACANCY_SHOW_CANDIDATES, [
                'id' => $builder->getVacancy()->getId()
            ]);
        }

        $formDecline = $this->createForm(CandidateDeclinePropositionType::class);
        $formDecline->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($formDecline->isSubmitted() && $formDecline->isValid()) {
            if ($builder->getCandidateVacancy() !== null) {
                $entityManager->persist($builder->getCandidateVacancy()->setCandidateStatus('Closed. Candidate declined proposition'));
            }
            if ($builder->getCandidateLink() !== null) {
                $entityManager->persist($builder->getCandidateLink()->setCandidateStatus('Closed. Candidate declined proposition'));
            }
            $entityManager->flush();
            return $this->redirectToRoute('candidate_after_interview_deny_by_candidate', [
                'id' => $builder->getCandidate()->getId(),
                self::VACANCY_ENTITY_IN_VIEW => $builder->getVacancy()->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/recruiterAfterInterview/candidateShowAfterInterview.html.twig',
            [
                self::BUILDER => $builder,
                'form' => $form->createView(),
                'formDecline' => $formDecline->createView()
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/deny/candidate/{id}", name="candidate_after_interview_deny_by_candidate", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param CreateNewDenyReasonCandidate $createNewDenyReasonCandidate
     * @param Request $request
     * @return Response
     * @throws NoDataException
     */
    public function candidateDeny(Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        CreateNewDenyReasonCandidate $createNewDenyReasonCandidate,
        Request $request): Response
    {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );
        $candidateOfferDeny = new CandidateOfferDeny();
        $form = $this->createForm(DenyAfterInterviewByCandidateType::class, $candidateOfferDeny);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $createNewDenyReasonCandidate->newDenyReasonCandidate($form->get(self::CHOICES)->getData(), $candidateOfferDeny);
            if ($builder->getCandidateVacancy() !== null) {
                $entityManager->persist($candidateOfferDeny->setCandidateVacancy($builder->getCandidateVacancy()));
            }
            if ($builder->getCandidateLink() !== null) {
                $entityManager->persist($candidateOfferDeny->setCandidateLink($builder->getCandidateLink()));
            }

            $entityManager->persist($candidateOfferDeny);
            $entityManager->flush();
            if (!empty($createNewDenyReasonCandidate->notSuitableSalary($form->get(self::CHOICES)->getData()))){
                return $this->redirectToRoute('candidate_after_interview_deny_by_candidate_salary_reason', [
                    'id' => $candidateOfferDeny->getId(),
                    self::VACANCY_ENTITY_IN_VIEW => $builder->getVacancy()->getId()
                ]);
            }
            return $this->redirectToRoute(self::VACANCY_SHOW_CANDIDATES, [
                'id' => $builder->getVacancy()->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/recruiterAfterInterview/candidateDenyShowAfterInterview.html.twig',
            [
                self::BUILDER => $builder,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/deny/candidate/salary/reason/{id}", name="candidate_after_interview_deny_by_candidate_salary_reason", methods={"GET","POST"})
     * @param CandidateOfferDeny $candidateOfferDeny
     * @param Request $request
     * @return Response
     */
    public function candidateDenyNotSuitableSalary(CandidateOfferDeny $candidateOfferDeny, Request $request): Response
    {

        $form = $this->createForm(DenyAfterInterviewNotSuitableSalaryType::class, $candidateOfferDeny);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($candidateOfferDeny);
            $entityManager->flush();
            return $this->redirectToRoute(self::VACANCY_SHOW_CANDIDATES, [
                'id' => $request->get(self::VACANCY_ENTITY_IN_VIEW)
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/recruiterAfterInterview/notSuitableSalary.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/candidate/backfeed/{id}", name="candidate_after_interview_candidate_backfeed", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param Request $request
     * @return Response
     * @throws NoDataException
     */
    public function backfeedToCandidate(Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        Request $request): Response
    {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );
        if ($builder->getCandidateVacancy() !== null){
            $form = $this->createForm(RecruiterReportedType::class, $builder->getCandidateVacancy()->getCandidateManagerDeny());
        }else{
            if ($builder->getCandidateLink() === null){
                throw new NoDataException('CandidateLink not found');
            }
            $form = $this->createForm(RecruiterReportedType::class, $builder->getCandidateLink()->getCandidateManagerDeny());
        }

        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($builder->getCandidateVacancy() !== null){
                $entityManager->persist($builder->getCandidateVacancy()->getCandidateManagerDeny());
            }else{
                $entityManager->persist($builder->getCandidateLink()->getCandidateManagerDeny());
            }
            $entityManager->flush();
            return $this->redirectToRoute(self::VACANCY_SHOW_CANDIDATES, [
                'id' => $builder->getVacancy()->getId()
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/recruiterAfterInterview/backfeedToCandidate.html.twig',
            [
                'builder' => $builder,
                'form' => $form->createView()
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/start-date/calendar/{id}", name="candidate_after_interview_start-date_calendar", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @return Response
     */
    public function startDateEmployeeCalendar(Vacancy $vacancy, Request $request, CandidateForms $candidateForms): Response
    {
        $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
        $candidateLink = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));
        return $this->render('recruiting/vacancy/showRecruiter/startDateEmployee/startDateEmployeeCalendar.html.twig',
            [
                'vacancy' => $vacancy,
                self::CANDIDATE_VACANCY => $candidateVacancy,
                self::CANDIDATE_LINK => $candidateLink
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/start-date/new/{id}", name="candidate_after_interview_start-date_new", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws LoaderError
     * @throws NoDataException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function setStartDateEmployee(Vacancy $vacancy,
                                        Request $request,
                                        CandidateForms $candidateForms,
                                        Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(AssignStartDateEmployeeType::class);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK)) !== null){
                $candidateVacancy = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));
                $candidateVacancyProvider = new CandidateLinkProvider($candidateVacancy);
            }else{
                $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
                $candidateVacancyProvider = new CandidateVacancyProvider($candidateVacancy);
            }
            if ($candidateVacancy === null){
                throw new NoDataException('CandidateVacancyLink not found');
            }

            $entityManager->persist($candidateVacancy->setDateStartWork($form->get(self::START_DATE_EMPLOYEE)->getData()));
            if ($vacancy->getVacancyViewerUser() !== null){
                $messageBuilderViewer = new CreateEmployeeStartDateForViewer($this->environment);
                $mailer->send($messageBuilderViewer->build($candidateVacancyProvider));
            }
            $messageBuilder = new CreateEmployeeStartDateForManager($this->environment);
            $messageBuilderRecruiter = new CreateEmployeeStartDateForRecruiter($this->environment);
            $mailer->send($messageBuilder->build($candidateVacancyProvider));
            $mailer->send($messageBuilderRecruiter->build($candidateVacancyProvider));
            $entityManager->flush();
            return $this->redirectToRoute(self::VACANCY_SHOW_CANDIDATES, [
                'id' => $vacancy->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/startDateEmployee/newStartDateEmployee.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @IsGranted("ROLE_RECRUITER")
     * @Route("/vacancy/candidate/after/interview/start-date/edit/{id}", name="candidate_after_interview_start-date_edit", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws NoDataException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editStartDateEmployee(Vacancy $vacancy,
        Request $request,
        CandidateForms $candidateForms,
        Swift_Mailer $mailer):Response
    {
        $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
        $candidateLink = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));

        $form = $this->createForm(AssignStartDateEmployeeType::class);
        if ($candidateVacancy !== null){
            $form->get(self::START_DATE_EMPLOYEE)->setData($candidateVacancy->getDateStartWork());
        }elseif($candidateLink !== null){
            $form->get(self::START_DATE_EMPLOYEE)->setData($candidateLink->getDateStartWork());
        }else{
            throw new NoDataException('CandidateLinkVacancy not found');
        }
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($candidateLink !== null){
                $candidateVacancyProvider = new CandidateLinkProvider($candidateLink);
                $entityManager->persist($candidateLink->setDateStartWork($form->get(self::START_DATE_EMPLOYEE)->getData()));
            }else{
                $candidateVacancyProvider = new CandidateVacancyProvider($candidateVacancy);
                $entityManager->persist($candidateVacancy->setDateStartWork($form->get(self::START_DATE_EMPLOYEE)->getData()));
            }
            if ($vacancy->getVacancyViewerUser() !== null){
                $messageBuilderViewer = new CreateEmployeeStartDateForViewerEdit($this->environment);
                $mailer->send($messageBuilderViewer->build($candidateVacancyProvider));
            }
            $messageBuilder = new CreateEmployeeStartDateForManagerEdit($this->environment);
            $messageBuilderRecruiter = new CreateEmployeeStartDateForRecruiterEdit($this->environment);
            $mailer->send($messageBuilder->build($candidateVacancyProvider));
            $mailer->send($messageBuilderRecruiter->build($candidateVacancyProvider));
            $entityManager->flush();
            return $this->redirectToRoute(self::VACANCY_SHOW_CANDIDATES, [
                'id' => $vacancy->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/startDateEmployee/editStartDateEmployee.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

}