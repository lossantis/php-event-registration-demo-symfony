<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationType;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration_index', methods: ['GET'])]
    public function index(#[Autowire(param: 'app.allowed_departments')] array $allowedDepartments): Response
    {
        return $this->render('registration/index.html.twig', [
            'allowedDepartments' => $allowedDepartments ?? [],
        ]);
    }

    #[Route('/registration/{department}', name: 'app_registration_new', methods: ['GET', 'POST'])]
    public function new(string $department, Request $request, RegistrationRepository $registrationRepository, #[Autowire(param: 'app.allowed_departments')] array $allowedDepartments): Response
    {
        // Validate department against allowed list (case-insensitive)
        $allowedDepartments = $allowedDepartments ?? [];
        $map = [];
        foreach ($allowedDepartments as $d) {
            $map[strtolower($d)] = $d; // keep canonical casing from config
        }
        $key = strtolower($department);
        if (!isset($map[$key])) {
            throw $this->createNotFoundException('Unknown department');
        }
        $canonicalDepartment = $map[$key];

        $registration = new Registration();
        $registration->setDepartment($canonicalDepartment);

        $form = $this->createForm(RegistrationType::class, $registration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrationRepository->save($registration, true);
            $this->addFlash('success', 'Thank you! Your registration has been received.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/new.html.twig', [
            'form' => $form->createView(),
            'department' => $canonicalDepartment,
        ]);
    }
}
