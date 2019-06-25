<?php


namespace App\Controller\Recruiting;


use App\Entity\Candidate;
use App\Entity\CommentViewer;
use App\Entity\Vacancy;
use App\Form\Recruiting\CandidateApproveByDepartmentManType;
use App\Form\Recruiting\CandidateLink\AssignDateInterviewLinkType;
use App\Form\Recruiting\CandidateLink\CandidateLinkDenialInterviewType;
use App\Form\Recruiting\CandidateVacancy\AssignDateInterviewType;
use App\Form\Recruiting\CandidateVacancy\CandidateVacancyDenialInterviewType;
use App\Form\Recruiting\CommentViewerType;
use App\Repository\CommentViewerRepository;
use App\Repository\VacancyRepository;
use App\Service\Candidate\CandidatePhotoDecorator;
use App\Service\CandidateForms\CandidateForms;
use App\Service\CandidateVacancyHistory\CandidateVacancyHistoryDataProvider;
use App\Service\Vacancy\CandidateApprove\DepartmentManagerApproveViewDTOBuilder;
use App\Service\Vacancy\CandidateEditRelationToCandidateLinkToCandidateVacancy\NoDataException;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateLinkProvider;
use App\Service\Vacancy\CreateCandidateVacancyLinkForLetter\CandidateVacancyProvider;
use App\Service\Vacancy\Letters\CreateForDepartmentManagerSetTime\CreateForDepartmentManagerSetTime;
use App\Service\Vacancy\Letters\CreateForDepartmentManagerSetTime\CreateForDepartmentManagerSetTimeEdit;
use App\Service\Vacancy\Letters\CreateForRecruiterSetTime\CreateForRecruiterSetTime;
use App\Service\Vacancy\Letters\CreateForRecruiterSetTime\CreateForRecruiterSetTimeEdit;
use App\Service\Vacancy\Letters\CreateForViewerSetTime\CreateForViewerSetTime;
use App\Service\Vacancy\Letters\CreateForViewerSetTime\CreateForViewerSetTimeEdit;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class CandidateApproveByDepartmentManagerController extends AbstractController
{

    public const VACANCY_ENTITY_IN_VIEW = 'vacancy';

    public const CANDIDATE = 'candidate';

    public const COMMENT_VIEWER = 'commentViewer';

    public const APPROVED_FOR_INTERVIEW = 'Approved for the interview';

    public const DATE_INTERVIEW = 'dateInterview';

    public const CONF_ROOM = 'confRoom';

    public const CANDIDATE_VACANCY = 'candidateVacancy';

    public const CANDIDATE_LINK = 'candidateLink';

    public const CANDIDATE_LINK_VACANCY_NOT_FOUND = 'CandidateLink or CandidateVacancy not found';

    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department-manager/{id}", name="vacancy_show_approved_interview_department_manager", methods={"GET","POST"})
     * @param DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder
     * @param CandidateVacancyHistoryDataProvider $candidateVacancyHistoryData
     * @param Candidate $candidate
     * @param Request $request
     * @return Response
     * @throws NoDataException
     */
    public function approveViewDepartmentManager(
        Candidate $candidate,
        DepartmentManagerApproveViewDTOBuilder $approveViewDTOBuilder,
        CandidateVacancyHistoryDataProvider $candidateVacancyHistoryData,
        Request $request
    ): Response {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $builder = $approveViewDTOBuilder->build(
            $candidate,
            $vacancyId
        );
        $form = $this->createForm(CandidateApproveByDepartmentManType::class);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($builder->getCandidateVacancy() !== null) {
                $entityManager->persist($builder->getCandidateVacancy()->setCandidateStatus(self::APPROVED_FOR_INTERVIEW));
                $candidateVacancyHistoryData->candidateVacancyCreate($builder->getCandidateVacancy(), self::APPROVED_FOR_INTERVIEW);
            }
            if ($builder->getCandidateLink() !== null) {
                $entityManager->persist($builder->getCandidateLink()->setCandidateStatus(self::APPROVED_FOR_INTERVIEW));
                $candidateVacancyHistoryData->candidateLinkCreate($builder->getCandidateLink(), self::APPROVED_FOR_INTERVIEW);
            }
            $entityManager->flush();
            return $this->redirectToRoute('vacancy_show_approved_interview_department_manager_approve', [
                'id' => $builder->getVacancy()->getId()
            ]);
        }

        $formViewer = $this->createForm(CommentViewerType::class);
        $formViewer->handleRequest($request);
        if ($formViewer->isSubmitted() && $formViewer->isValid()) {
            $commentViewer = new CommentViewer();
            $commentViewer->setVacancyViewerUser($builder->getVacancy()->getVacancyViewerUser());
            $commentViewer->setComment($formViewer->get('comment')->getData());
            if ($builder->getCandidateVacancy() !== null) {
                $commentViewer->setCandidateVacancy($builder->getCandidateVacancy());
            }
            if($builder->getCandidateLink() !== null){
                $commentViewer->setCandidateLink($builder->getCandidateLink());
            }
            $entityManager->persist($commentViewer);
            $entityManager->flush();
            return $this->redirectToRoute('vacancy_show_approved_interview_comment', [
                'id' => $builder->getVacancy()->getId(),
                self::COMMENT_VIEWER => $commentViewer->getId()
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/approveRequest.html.twig',
            [
                'builder' => $builder,
                'form' => $form->createView(),
                'formViewer' => $formViewer->createView()
            ])
            ;
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department-manager/approve/{id}", name="vacancy_show_approved_interview_department_manager_approve", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @return Response
     */
    public function approveCandidate(Vacancy $vacancy): Response
    {
        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/approveCandidate.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy
            ]);
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department-manager/comment/{id}", name="vacancy_show_approved_interview_comment", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CommentViewerRepository $commentViewerRepository
     * @return Response
     */
    public function addedComment(
        Vacancy $vacancy,
        Request $request,
        CommentViewerRepository $commentViewerRepository
    ): Response {
        $commentViewer = $commentViewerRepository->findOneBy(['id' => $request->get(self::COMMENT_VIEWER)]);
        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/addedComment.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy,
                self::COMMENT_VIEWER => $commentViewer
            ]);
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department-manager/candidate/show/{id}", name="approved_interview_candidate_show", methods={"GET","POST"})
     * @param Candidate $candidate
     * @param VacancyRepository $vacancyRepository
     * @param Request $request
     * @return Response
     * @throws NoDataException
     */
    public function candidateShow(
        Candidate $candidate,
        VacancyRepository $vacancyRepository,
        Request $request
    ): Response {
        $vacancyId = $request->get(self::VACANCY_ENTITY_IN_VIEW);
        $vacancy = $vacancyRepository->findOneBy(['id' => $vacancyId]);
        if ($vacancy === null) {
            throw new NoDataException('Vacancy not found');
        }
        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/candidateShow.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy,
                self::CANDIDATE => $candidate,
                'vacancies' => $candidate->getCandidateVacancies()

            ]);
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department-manager/deny/{id}", name="vacancy_show_approved_interview_department_manager_deny", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @param CandidatePhotoDecorator $candidatePhotoDecorator
     * @return Response
     */
    public function approveCandidateViewDepartmentManagerDeny(
        Vacancy $vacancy,
        Request $request,
        CandidateForms $candidateForms,
        CandidatePhotoDecorator $candidatePhotoDecorator
    ): Response {
        $candidateId = $request->get(self::CANDIDATE);
        $candidateVacancy = $candidateForms->candidateVacancySearch($vacancy, $candidateId);
        $candidateLink = $candidateForms->candidateLinkSearch($vacancy->getVacancyLinks(), $candidateId);
        $receivedCv = null;
        $form = null;
        if ($candidateVacancy !== null) {
            if ($candidateVacancy !== null){
                $receivedCv = $candidateVacancy->getReceivedCv();
            }
            $form = $this->createForm(CandidateVacancyDenialInterviewType::class, $candidateVacancy);
            $candidatePhotoDecorator->receivedCvNotNull($candidateVacancy);
        }
        if ($candidateLink !== null){
            if ($candidateLink->getReceivedCv() !== null){
                $receivedCv = $candidateLink->getReceivedCv();
            }
            $form = $this->createForm(CandidateLinkDenialInterviewType::class, $candidateLink);
            $candidatePhotoDecorator->receivedCvNotNullCandidateLink($candidateLink);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($candidateVacancy !== null) {
                $candidateVacancy->setReceivedCv($receivedCv);
                $candidateVacancy->setCandidateStatus('Closed by department manager');
                $entityManager->persist($candidateVacancy);
            }
            if ($candidateLink !== null) {
                $candidateLink->setReceivedCv($receivedCv);
                $candidateLink->setCandidateStatus('Closed by department manager');
                $entityManager->persist($candidateLink);
            }
            $entityManager->flush();

            return $this->redirectToRoute('vacancy_show_approved_interview_department_manager_candidates', [
                'id' => $vacancy->getId(),
//                self::VACANCY_ENTITY_IN_VIEW => $vacancy->getId()
            ]);
        }
        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/denyCandidate.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department-manager/list-candidates/{id}", name="vacancy_show_approved_interview_department_manager_candidates", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @return Response
     */
    public function listCandidates(Vacancy $vacancy): Response
    {

        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/vacancyListCandidates.html.twig',
            [
                self::VACANCY_ENTITY_IN_VIEW => $vacancy,

            ]);
    }

    /**
     * @Route("/vacancy/recruiter/approved/interview/department_manager/comment/viewer/{id}/edit", name="vacancy_show_approved_interview_department_manager_comment_edit", methods={"GET","POST"})
     * @param Request $request
     * @param CommentViewer $commentViewer
     * @return Response
     */
    public function commentViewerEdit(CommentViewer $commentViewer, Request $request): Response
    {
        $form = $this->createForm(CommentViewerType::class, $commentViewer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vacancy_show_approved_interview_comment', [
                'id' => $request->get(self::VACANCY_ENTITY_IN_VIEW),
                self::COMMENT_VIEWER => $commentViewer->getId()
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/departmentManagerApproveCandidate/addedCommentEdit.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/recruiter/approved/interview/assign/date/{id}", name="interview_assign_data", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @return Response
     */
    public function assignDateCalendar(Vacancy $vacancy, Request $request, CandidateForms $candidateForms): Response
    {
        $candidateLink = null;
        $candidateVacancy = null;
        if ($request->get(self::CANDIDATE_LINK) !== null){
            $candidateLink = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));
        }else{
            $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
        }

        return $this->render('recruiting/vacancy/showRecruiter/assignDataInterview.html.twig',
            [
                'vacancy' => $vacancy,
                self::CANDIDATE_LINK => $candidateLink,
                self::CANDIDATE_VACANCY => $candidateVacancy
            ]);
    }


    /**
     * @Route("/recruiter/approved/interview/assign/date/new/{id}", name="interview_assign_data_new", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws NoDataException
     */
    public function assignDateNew(Vacancy $vacancy,
        Request $request,
        CandidateForms $candidateForms,
        Swift_Mailer $mailer): Response
    {
        $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
        $candidateLink = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));
        if ($candidateVacancy !== null){
            $form = $this->createForm(AssignDateInterviewType::class);
        }else{
            $form = $this->createForm(AssignDateInterviewLinkType::class);
        }
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($candidateVacancy !== null){
                $candidateVacancy->setDateInterview($form->get(self::DATE_INTERVIEW)->getData());
                $candidateVacancy->setConfRoom($form->get(self::CONF_ROOM)->getData());
                $entityManager->persist($candidateVacancy);
                $candidateVacancyProvider = new CandidateVacancyProvider($candidateVacancy);

            }elseif($candidateLink !== null){
                $candidateLink->setDateInterview($form->get(self::DATE_INTERVIEW)->getData());
                $candidateLink->setConfRoom($form->get(self::CONF_ROOM)->getData());
                $entityManager->persist($candidateLink);
                $candidateVacancyProvider = new CandidateLinkProvider($candidateLink);
            }else{
                throw new NoDataException(self::CANDIDATE_LINK_VACANCY_NOT_FOUND);
            }
            if ($vacancy->getVacancyViewerUser() !== null){
                $messageBuilderViewer = new CreateForViewerSetTime($this->environment);
                $mailer->send($messageBuilderViewer->build($candidateVacancyProvider));
            }
            $messageBuilder = new CreateForDepartmentManagerSetTime($this->environment);
            $messageBuilderRecruiter = new CreateForRecruiterSetTime( $this->environment);
            $mailer->send($messageBuilder->build($candidateVacancyProvider));
            $mailer->send($messageBuilderRecruiter->build($candidateVacancyProvider));

           $entityManager->flush();

            return $this->redirectToRoute('vacancy_show_candidates', [
                'id' => $vacancy->getId()
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/assignDateInterviewNew.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/recruiter/approved/interview/assign/date/edit/{id}", name="interview_assign_data_edit", methods={"GET","POST"})
     * @param Vacancy $vacancy
     * @param Request $request
     * @param CandidateForms $candidateForms
     * @param Swift_Mailer $mailer
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws NoDataException
     */
    public function assignDateEdit(Vacancy $vacancy,
        Request $request,
        CandidateForms $candidateForms,
        Swift_Mailer $mailer): Response
    {
        $candidateVacancy = $candidateForms->candidateVacancyByIdSearch($request->get(self::CANDIDATE_VACANCY));
        $candidateLink = $candidateForms->candidateLinkByIdSearch($request->get(self::CANDIDATE_LINK));
        if ($candidateVacancy !== null){
            $form = $this->createForm(AssignDateInterviewType::class);
            $form->get(self::DATE_INTERVIEW)->setData($candidateVacancy->getDateInterview());
            $form->get(self::CONF_ROOM)->setData($candidateVacancy->getConfRoom());
        }elseif($candidateLink !== null){
            $form = $this->createForm(AssignDateInterviewLinkType::class);
            $form->get(self::DATE_INTERVIEW)->setData($candidateLink->getDateInterview());
            $form->get(self::CONF_ROOM)->setData($candidateLink->getConfRoom());
        }else{
            throw new NoDataException(self::CANDIDATE_LINK_VACANCY_NOT_FOUND);
        }

        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($candidateVacancy !== null){
                $candidateVacancy->setDateInterview($form->get(self::DATE_INTERVIEW)->getData());
                $candidateVacancy->setConfRoom($form->get(self::CONF_ROOM)->getData());
                $entityManager->persist($candidateVacancy);
                $candidateVacancyProvider = new CandidateVacancyProvider($candidateVacancy);
            }elseif($candidateLink !== null){
                $candidateLink->setDateInterview($form->get(self::DATE_INTERVIEW)->getData());
                $candidateLink->setConfRoom($form->get(self::CONF_ROOM)->getData());
                $entityManager->persist($candidateLink);
                $candidateVacancyProvider = new CandidateLinkProvider($candidateLink);
            }else{
                throw new NoDataException(self::CANDIDATE_LINK_VACANCY_NOT_FOUND);
            }
            if ($vacancy->getVacancyViewerUser() !== null){
                $messageBuilderViewer = new CreateForViewerSetTimeEdit($this->environment);
                $mailer->send($messageBuilderViewer->build($candidateVacancyProvider));
            }
            $messageBuilder = new CreateForDepartmentManagerSetTimeEdit($this->environment);
            $messageBuilderRecruiter = new CreateForRecruiterSetTimeEdit($this->environment);
            $mailer->send($messageBuilder->build($candidateVacancyProvider));
            $mailer->send($messageBuilderRecruiter->build($candidateVacancyProvider));

            $entityManager->flush();

            return $this->redirectToRoute('vacancy_show_candidates', [
                'id' => $vacancy->getId()
            ]);
        }

        return $this->render('recruiting/vacancy/showRecruiter/assignDateInterviewNew.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }

}