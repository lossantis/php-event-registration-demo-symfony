<?php

declare(strict_types=1);

namespace App\Message;

final class SendRegistrationConfirmation
{
    public function __construct(
        private readonly int $registrationId
    ) {
    }

    public function getRegistrationId(): int
    {
        return $this->registrationId;
    }
}
