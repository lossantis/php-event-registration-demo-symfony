<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SendRegistrationConfirmation;
use App\Repository\RegistrationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
final class SendRegistrationConfirmationHandler
{
    public function __construct(
        private readonly RegistrationRepository $registrations,
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly string $defaultFrom
    ) {
    }

    public function __invoke(SendRegistrationConfirmation $message): void
    {
        $registration = $this->registrations->find($message->getRegistrationId());
        if (!$registration) {
            return; // nothing to do
        }
        if ($registration->isConfirmed()) {
            return; // already confirmed
        }
        $token = $registration->getConfirmationToken();
        if (!$token) {
            return; // token should be set before dispatch
        }

        $url = $this->urlGenerator->generate('app_registration_confirm', [
            'token' => $token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new TemplatedEmail())
            ->from($this->defaultFrom)
            ->to($registration->getEmail())
            ->subject('Please confirm your registration')
            ->htmlTemplate('registration/confirmation_email.html.twig')
            ->context([
                'registration' => $registration,
                'confirmationUrl' => $url,
            ]);

        $this->mailer->send($email);
    }
}
