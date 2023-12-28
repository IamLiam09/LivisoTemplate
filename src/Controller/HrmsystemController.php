<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\HRMSystem\Trip;
use App\Form\HRMSystem\TripType;
use App\Entity\HRMSystem\Warning;
use App\Entity\HRMSystem\Holidays;
use App\Form\HRMSystem\WarningType;
use App\Entity\HRMSystem\Complaints;
use App\Form\HRMSystem\HolidaysType;
use App\Entity\HRMSystem\ManageLeave;
use App\Entity\HRMSystem\Resignation;
use App\Form\HRMSystem\ComplaintsType;
use App\Form\HRMSystem\ManageLeaveType;
use App\Form\HRMSystem\ResignationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\HRMSystem\CustomQuestions;
use App\Form\HRMSystem\CustomQuestionsType;
use App\Entity\HRMSystem\EmployeesAssetSetup;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\HRMSystem\EmployeesAssetSetupType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HrmsystemController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/hrmsystem/employee_setup', name: 'hrmsystem/employee_setup')]
    public function employeeSetup(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/employeeSetup.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/set_salary', name: 'hrmsystem/set_salary')]
    public function setSalary(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/setSalary.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/payslip', name: 'hrmsystem/payslip')]
    public function payslip(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/payslip.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/manage_leave', name: 'hrmsystem/manage_leave')]
    public function manageLeave(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $manageleave = new ManageLeave();
        $form = $this->createForm(ManageLeaveType::class, $manageleave);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($manageleave);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/manage_leave');
        }

        $repository = $this->entityManager->getRepository(ManageLeave::class);
        $manageleaves = $repository->findAll();

        return $this->render('hrmsystem/manageleave.html.twig', [
            'controller_name' => 'HrmsystemController',
            'manageLeaves' => $manageleaves,
            'form' => $form->createView(),
        ]);
    }
    // delete manage leave
    #[Route('/manage_leave/delete/{id}', name: 'manageleave_delete', methods: ["GET", "POST"])]
    public function manageleaveDelete(Manageleave $Manageleave): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($Manageleave);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/manage_leave');
        // return new Response('post was deleted');
    }
    // edit manage_leave
    #[Route("/hrmsystem/manage_leave/{id}/edit", name: "manageleave_edit", methods: ["GET", "PUT", "POST"])]
    public function manage_leaveEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(Manageleave::class);
        $manageleave = $repository->find($id);

        if (!$manageleave) {
            throw $this->createNotFoundException('manageleave not found');
        }

        $form = $this->createForm(ManageleaveType::class, $manageleave);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $manageleave = $form->getData();
                $this->entityManager->persist($manageleave);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/manage_leave');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(Manageleave::class);
        $manageleaves = $repository->findAll();

        return $this->render('hrmsystem/edit/manageleave.html.twig', [
            'controllername' => 'HrmsystemController',
            'form' => $form->createView(),
            'manageLeaves' => $manageleaves,
        ]);
    }
    #[Route('/hrmsystem/bulk_attendance', name: 'hrmsystem/bulk_attendance')]
    public function bulkAttendance(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/bulkAttendance.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/mark_attendance', name: 'hrmsystem/mark_attendance')]
    public function markAttendance(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/markAttendance.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/indicator', name: 'hrmsystem/indicator')]
    public function indicator(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/indicator.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/appraisal', name: 'hrmsystem/appraisal')]
    public function appraisal(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/appraisal.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/goal_tracking', name: 'hrmsystem/goal_tracking')]
    public function goalTracking(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/goalTracking.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/training_list', name: 'hrmsystem/training_list')]
    public function trainingList(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/trainingList.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/trainer', name: 'hrmsystem/trainer')]
    public function trainer(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/trainer.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/jobs', name: 'hrmsystem/jobs')]
    public function jobs(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/jobs.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/job_create', name: 'hrmsystem/job_create')]
    public function jobCreate(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/jobCreate.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/job_application', name: 'hrmsystem/job_application')]
    public function jobApplication(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/jobApplication.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/job_candidate', name: 'hrmsystem/job_candidate')]
    public function jobCandidate(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/jobCandidate.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/job_on-boarding', name: 'hrmsystem/job_on-boarding')]
    public function jobOnBoarding(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/jobOnBoarding.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/custom_question', name: 'hrmsystem/custom_question')]
    public function customQuestion(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $customQuestion = new CustomQuestions();
        $form = $this->createForm(CustomQuestionsType::class, $customQuestion);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($customQuestion);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/custom_question');
        }

        $repository = $this->entityManager->getRepository(CustomQuestions::class);
        $customQuestions = $repository->findAll();

        return $this->render('hrmsystem/customQuestion.html.twig', [
            'controller_name' => 'HrmsystemController',
            'customQuestions' => $customQuestions,
            'form' => $form->createView(),
        ]);
    }
    // delete customQuestions
    #[Route('/hrmsystem/customQuestions/delete/{id}', name: 'customQuestions_delete', methods: ["GET", "POST"])]
    public function customQuestionsDelete(CustomQuestions $customQuestions): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($customQuestions);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/custom_question');
    }

    // edit customQuestion
    #[Route("/hrmsystem/customQuestion/{id}/edit", name: "customQuestions_edit", methods: ["GET", "PUT", "POST"])]
    public function customQuestionEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(CustomQuestions::class);
        $customQuestion = $repository->find($id);

        if (!$customQuestion) {
            throw $this->createNotFoundException('customQuestion not found');
        }

        $form = $this->createForm(CustomQuestionsType::class, $customQuestion);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $customQuestion = $form->getData();
                $this->entityManager->persist($customQuestion);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/custom_question');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(CustomQuestions::class);
        $customQuestions = $repository->findAll();

        return $this->render('hrmsystem/edit/customQuestion.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'customQuestions' => $customQuestions,
        ]);
    }


    #[Route('/hrmsystem/interview_schedule', name: 'hrmsystem/interview_schedule')]
    public function interviewSchedule(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/interviewSchedule.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/career', name: 'hrmsystem/career')]
    public function career(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/career.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/award', name: 'hrmsystem/award')]
    public function award(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/award.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/transfer', name: 'hrmsystem/transfer')]
    public function transfer(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/transfer.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    // Resignation
    #[Route('/hrmsystem/resignation', name: 'hrmsystem/resignation')]
    public function resignation(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $resignation = new Resignation();
        $form = $this->createForm(ResignationType::class, $resignation);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($resignation);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/resignation');
        }

        $repository = $this->entityManager->getRepository(Resignation::class);
        $resignations = $repository->findAll();

        return $this->render('hrmsystem/resignation.html.twig', [
            'controller_name' => 'HrmsystemController',
            'resignations' => $resignations,
            'form' => $form->createView(),
        ]);
    }
    // delete resignation
    #[Route('/resignation/delete/{id}', name: 'resignation_delete', methods: ["GET", "POST"])]
    public function resignationDelete(Resignation $resignation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($resignation);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/resignation');
    }
    // edit resignation
    #[Route("/hrmsystem/resignation/{id}/edit", name: "resignation_edit", methods: ["GET", "PUT", "POST"])]
    public function resignationEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(Resignation::class);
        $resignation = $repository->find($id);

        if (!$resignation) {
            throw $this->createNotFoundException('Resignation not found');
        }

        $form = $this->createForm(ResignationType::class, $resignation);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $resignation = $form->getData();
                $this->entityManager->persist($resignation);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/resignation');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(Resignation::class);
        $resignations = $repository->findAll();

        return $this->render('hrmsystem/edit/resignation.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'resignations' => $resignations,
        ]);
    }

    #[Route('/hrmsystem/trip', name: 'hrmsystem/trip')]
    public function trip(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $trip = new Trip();
        $form = $this->createForm(TripType::class, $trip);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($trip);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/trip');
        }

        $repository = $this->entityManager->getRepository(Trip::class);
        $trips = $repository->findAll();

        return $this->render('hrmsystem/trip.html.twig', [
            'controller_name' => 'HrmsystemController',
            'trips' => $trips,
            'form' => $form->createView(),
        ]);
    }
    // delete trip
    #[Route('/hrmsystem/trip/delete/{id}', name: 'trip_delete', methods: ["GET", "POST"])]
    public function tripDelete(Trip $trip): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($trip);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/trip');
    }
    // edit trip
    #[Route("/hrmsystem/trip/{id}/edit", name: "trip_edit", methods: ["GET", "PUT", "POST"])]
    public function tripEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(Trip::class);
        $trip = $repository->find($id);

        if (!$trip) {
            throw $this->createNotFoundException('trip not found');
        }

        $form = $this->createForm(TripType::class, $trip);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $trip = $form->getData();
                $this->entityManager->persist($trip);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/trip');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(Trip::class);
        $trips = $repository->findAll();

        return $this->render('hrmsystem/edit/trip.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'trips' => $trips,
        ]);
    }


    #[Route('/hrmsystem/promotion', name: 'hrmsystem/promotion')]
    public function promotion(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/promotion.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/complaints/{id}', name: 'hrmsystem/complaints', methods: ["GET", "POST"])]
    public function complaints(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $currentUser = $this->getUser();
        assert($currentUser instanceof User);
        $complaints = new Complaints();
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(ComplaintsType::class, $complaints,  ['current_user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($complaints);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/complaints', ['id' => $id]);
        }

        $repository = $this->entityManager->getRepository(Complaints::class);
        $complaintss = $repository->findBy(['user' => $currentUser]);

        return $this->render('hrmsystem/complaints.html.twig', [
            'controller_name' => 'HrmsystemController',
            'complaintss' => $complaintss,
            'form' => $form->createView(),
        ]);
    }
    // delete complaints
    #[Route('/hrmsystem/{id}complaints/delete/{user_id}', name: 'complaints_delete', methods: ["GET", "POST"])]
    public function ComplaintsDelete(Complaints $complaints, int $id, int $user_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($complaints);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/complaints', ['id' => $id]);
    }
    // edit complaints
    #[Route("/hrmsystem/complaints/{id}/edit/{user_id}", name: "complaints_edit", methods: ["GET", "PUT", "POST"])]
    public function complaintsEdit(Request $request, int $id, int $user_id): Response
    {
        $currentUser = $this->getUser();
        assert($currentUser instanceof User);
        $repository = $this->entityManager->getRepository(Complaints::class);
        $complaints = $repository->find($id);

        if (!$complaints) {
            throw $this->createNotFoundException('complaints not found');
        }

        $form = $this->createForm(ComplaintsType::class, $complaints, ['current_user' => $this->getUser()]);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $complaints = $form->getData();
                $this->entityManager->persist($complaints);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/complaints', ['id' => $user_id]);
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(Complaints::class);
        $complaintss = $repository->findBy(['user' => $currentUser]);

        return $this->render('hrmsystem/edit/complaints.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'complaintss' => $complaintss,
        ]);
    }
    #[Route('/hrmsystem/warning', name: 'hrmsystem/warning')]
    public function warning(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $warning = new Warning();
        $form = $this->createForm(WarningType::class, $warning);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($warning);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/warning');
        }

        $repository = $this->entityManager->getRepository(Warning::class);
        $warnings = $repository->findAll();

        return $this->render('hrmsystem/warning.html.twig', [
            'controller_name' => 'HrmsystemController',
            'warnings' => $warnings,
            'form' => $form->createView(),
        ]);
    }
    // delete warning
    #[Route('/hrmsystem/warning/delete/{id}', name: 'warning_delete', methods: ["GET", "POST"])]
    public function warningDelete(Warning $warning): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($warning);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/warning');
    }
    // edit warning
    #[Route("/hrmsystem/warning/{id}/edit", name: "warning_edit", methods: ["GET", "PUT", "POST"])]
    public function warningEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(Warning::class);
        $warning = $repository->find($id);

        if (!$warning) {
            throw $this->createNotFoundException('warning not found');
        }

        $form = $this->createForm(WarningType::class, $warning);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $warning = $form->getData();
                $this->entityManager->persist($warning);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/warning');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(Warning::class);
        $warnings = $repository->findAll();

        return $this->render('hrmsystem/edit/warning.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'warnings' => $warnings,
        ]);
    }
    #[Route('/hrmsystem/termination', name: 'hrmsystem/termination')]
    public function termination(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/termination.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/announcement', name: 'hrmsystem/announcement')]
    public function announcement(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/announcement.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/holidays', name: 'hrmsystem/holidays')]
    public function holidays(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $holidays = new Holidays();
        $form = $this->createForm(HolidaysType::class, $holidays);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($holidays);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/holidays');
        }

        $repository = $this->entityManager->getRepository(Holidays::class);
        $holidayss = $repository->findAll();

        return $this->render('hrmsystem/holidays.html.twig', [
            'controller_name' => 'HrmsystemController',
            'holidayss' => $holidayss,
            'form' => $form->createView(),
        ]);
    }
    // holiday delete
    #[Route('/hrmsystem/holidays/delete/{id}', name: 'holidays_delete', methods: ["GET", "POST"])]
    public function holidaysDelete(Holidays $holidays): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($holidays);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/holidays');
    }
    // holiday edit
    #[Route("/hrmsystem/holidays/{id}/edit", name: "holidays_edit", methods: ["GET", "PUT", "POST"])]
    public function holidaysEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(Holidays::class);
        $holidays = $repository->find($id);

        if (!$holidays) {
            throw $this->createNotFoundException('holidays not found');
        }

        $form = $this->createForm(HolidaysType::class, $holidays);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $holidays = $form->getData();
                $this->entityManager->persist($holidays);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/holidays');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(Holidays::class);
        $holidayss = $repository->findAll();

        return $this->render('hrmsystem/edit/holidays.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'holidayss' => $holidayss,
        ]);
    }
    #[Route('/hrmsystem/event_setup', name: 'hrmsystem/event_setup')]
    public function eventSetup(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/eventSetup.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/meeting', name: 'hrmsystem/meeting')]
    public function meeting(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/meeting.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/employees_asset_setup', name: 'hrmsystem/employees_asset_setup')]
    public function employeesAssetSetup(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $employeesAssetSetup = new EmployeesAssetSetup();
        $form = $this->createForm(EmployeesAssetSetupType::class, $employeesAssetSetup);

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity only if the form is submitted and valid
            $this->entityManager->persist($employeesAssetSetup);
            $this->entityManager->flush();

            // Redirect after successful form submission (optional)
            return $this->redirectToRoute('hrmsystem/employees_asset_setup');
        }

        $repository = $this->entityManager->getRepository(EmployeesAssetSetup::class);
        $employeesAssetSetups = $repository->findAll();

        return $this->render('hrmsystem/employeesAssetSetup.html.twig', [
            'controller_name' => 'HrmsystemController',
            'employeesAssetSetups' => $employeesAssetSetups,
            'form' => $form->createView(),
        ]);
    }
    // delete employeesAssetSetupForm
    #[Route('/hrmsystem/employees_asset_setup/delete/{id}', name: 'employeesAssetSetup_delete', methods: ["GET", "POST"])]
    public function employeesAssetSetupDelete(EmployeesAssetSetup $employeesAssetSetup): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->entityManager->remove($employeesAssetSetup);
        $this->entityManager->flush();
        return $this->redirectToRoute('hrmsystem/employees_asset_setup');
    }
    // edit resignation
    #[Route("/hrmsystem/employees_asset_setup/{id}/edit", name: "employeesAssetSetup_edit", methods: ["GET", "PUT", "POST"])]
    public function employeesAssetSetupEdit(Request $request, $id): Response
    {
        $repository = $this->entityManager->getRepository(EmployeesAssetSetup::class);
        $employeesAssetSetup = $repository->find($id);

        if (!$employeesAssetSetup) {
            throw $this->createNotFoundException('employeesAssetSetup not found');
        }

        $form = $this->createForm(EmployeesAssetSetupType::class, $employeesAssetSetup);
        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Persist the entity only if the form is submitted and valid
                $employeesAssetSetup = $form->getData();
                $this->entityManager->persist($employeesAssetSetup);
                $this->entityManager->flush();

                // Redirect after successful form submission (optional)
                return $this->redirectToRoute('hrmsystem/employees_asset_setup');
            }
        } catch (\Exception $error) {
            $this->addFlash('danger', 'An error occurred while processing the form.');
            throw $error;
        }
        $repository = $this->entityManager->getRepository(employeesAssetSetup::class);
        $employeesAssetSetups = $repository->findAll();

        return $this->render('hrmsystem/edit/employeesAssetSetup.html.twig', [
            'controller_name' => 'HrmsystemController',
            'form' => $form->createView(),
            'employeesAssetSetups' => $employeesAssetSetups,
        ]);
    }
    #[Route('/hrmsystem/document_setup', name: 'hrmsystem/document_setup')]
    public function documentSetup(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/documentSetup.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/company_policy', name: 'hrmsystem/company_policy')]
    public function companyPolicy(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/companyPolicy.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
    #[Route('/hrmsystem/hrm_system_setup', name: 'hrmsystem/hrm_system_setup')]
    public function hrmSystemSetup(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('hrmsystem/hrmSystemSetup.html.twig', [
            'controller_name' => 'HrmsystemController',
        ]);
    }
}
