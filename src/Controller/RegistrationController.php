<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Registration;
use App\Form\RegistrationType;
use App\Message\SendRegistrationConfirmation;
use App\Repository\RegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\MessageBusInterface;

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
    public function new(string $department, Request $request, RegistrationRepository $registrationRepository, MessageBusInterface $bus, #[Autowire(param: 'app.allowed_departments')] array $allowedDepartments): Response
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
            // Generate confirmation token and timestamp
            $registration->setConfirmationToken(self::generateToken());
            $registration->setConfirmationSentAt(new \DateTimeImmutable());

            $registrationRepository->save($registration, true);

            // Dispatch async email
            $bus->dispatch(new SendRegistrationConfirmation($registration->getId()));

            // Store translation key; Twig will translate flash messages via |trans in base template
            $this->addFlash('success', 'flash.registration.success');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/new.html.twig', [
            'form' => $form->createView(),
            'department' => $canonicalDepartment,
        ]);
    }

    #[Route('/registration/confirm/{token}', name: 'app_registration_confirm', methods: ['GET'])]
    public function confirm(string $token, RegistrationRepository $registrationRepository): Response
    {
        $registration = $registrationRepository->findOneBy(['confirmationToken' => $token]);
        if (!$registration) {
            return $this->render('registration/confirmation_result.html.twig', [
                'status' => 'invalid',
            ]);
        }

        // check already confirmed
        if ($registration->isConfirmed()) {
            return $this->render('registration/confirmation_result.html.twig', [
                'status' => 'already',
                'registration' => $registration,
            ]);
        }

        $sentAt = $registration->getConfirmationSentAt();
        $expiresAt = $sentAt ? (clone $sentAt)->modify('+24 hours') : null;
        if (!$expiresAt || new \DateTimeImmutable() > $expiresAt) {
            // Delete the registration if the confirmation link has expired
            $registrationRepository->remove($registration, true);
            return $this->render('registration/confirmation_result.html.twig', [
                'status' => 'expired',
                'registration' => $registration,
            ]);
        }

        $registration->setConfirmedAt(new \DateTimeImmutable());
        $registration->setConfirmationToken(null);
        $registrationRepository->save($registration, true);

        return $this->render('registration/confirmation_result.html.twig', [
            'status' => 'success',
            'registration' => $registration,
        ]);
    }

    private static function generateToken(): string
    {
        // 32-char hex token
        return bin2hex(random_bytes(16));
    }
}
