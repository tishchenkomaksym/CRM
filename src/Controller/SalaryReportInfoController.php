<?php

namespace App\Controller;

use App\Entity\SalaryReportInfo;
use App\Form\SalaryReportInfoType;
use App\Repository\SalaryReportInfoRepository;
use App\Repository\UserRepository;
use App\Service\SalaryReport\Builder\BaseSalaryReportBuilder;
use App\Service\SalaryReport\SalaryReportDTO;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_VIEW_SALARY_REPORT")
 * @Route("/salary/report/info")
 */
class SalaryReportInfoController extends AbstractController
{
    public const SALARY_REPORT_INFO = 'salary_report_info';
    public const SALARY_REPORT_INFO_INDEX = 'salary_report_info_index';

    /**
     * @Route("/", name="salary_report_info_index", methods={"GET"})
     * @param SalaryReportInfoRepository $salaryReportInfoRepository
     * @param SalaryReportInfoRepository $reportInfoRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(SalaryReportInfoRepository $salaryReportInfoRepository,
                            SalaryReportInfoRepository $reportInfoRepository): Response
    {
        $nextSalaryReport = $reportInfoRepository->getNextSalaryReport(new DateTime());
        return $this->render('salary_report_info/index.html.twig', [
            'salary_report_infos' => $salaryReportInfoRepository->findAll(),
            'next_salary_report' => $nextSalaryReport
        ]);
    }

    /**
     * @Route("/new", name="salary_report_info_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $salaryReportInfo = new SalaryReportInfo();
        $form = $this->createForm(SalaryReportInfoType::class, $salaryReportInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($salaryReportInfo);
            $entityManager->flush();

            return $this->redirectToRoute(self::SALARY_REPORT_INFO_INDEX);
        }

        return $this->render('salary_report_info/new.html.twig', [
            self::SALARY_REPORT_INFO => $salaryReportInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salary_report_info_show", methods={"GET"})
     * @param SalaryReportInfoRepository $salaryReportInfoRepository
     * @param SalaryReportInfo $salaryReportInfo
     * @param BaseSalaryReportBuilder $baseSalaryReportBuilder
     * @param UserRepository $userRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function show(
        SalaryReportInfoRepository $salaryReportInfoRepository,
        SalaryReportInfo $salaryReportInfo,
        BaseSalaryReportBuilder $baseSalaryReportBuilder,
        UserRepository $userRepository
    ): Response {
        $users = $userRepository->findAll();
        /** @var SalaryReportDTO[] $salaryReportDtoArray */
        $salaryReportDtoArray = [];
        $previousReport = $salaryReportInfoRepository->getPreviousReport($salaryReportInfo);
        foreach ($users as $user) {
            $salaryReportDtoArray[] = $baseSalaryReportBuilder->build($previousReport, $salaryReportInfo, $user);
        }
        return $this->render('salary_report_info/show.html.twig', [
            self::SALARY_REPORT_INFO => $salaryReportInfo,
            'salaryReportItems' => $salaryReportDtoArray
        ]);
    }

    /**
     * @Route("/{id}/edit", name="salary_report_info_edit", methods={"GET","POST"})
     * @param Request $request
     * @param SalaryReportInfo $salaryReportInfo
     * @return Response
     */
    public function edit(Request $request, SalaryReportInfo $salaryReportInfo): Response
    {
        $form = $this->createForm(SalaryReportInfoType::class, $salaryReportInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(self::SALARY_REPORT_INFO_INDEX, [
                'id' => $salaryReportInfo->getId(),
            ]);
        }

        return $this->render('salary_report_info/edit.html.twig', [
            self::SALARY_REPORT_INFO => $salaryReportInfo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="salary_report_info_delete", methods={"DELETE"})
     * @param Request $request
     * @param SalaryReportInfo $salaryReportInfo
     * @return Response
     */
    public function delete(Request $request, SalaryReportInfo $salaryReportInfo): Response
    {
        if ($this->isCsrfTokenValid('delete' . $salaryReportInfo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($salaryReportInfo);
            $entityManager->flush();
        }

        return $this->redirectToRoute(self::SALARY_REPORT_INFO_INDEX);
    }
}
