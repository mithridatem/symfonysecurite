<?php

namespace App\Service;

class EmailService
{
    public function __construct(
        private string $userEmail,
        private string $pwdEmail,
        private string $smtpEmail,
        private int $portSmtpEmail
    ) {}

    public function test() {
        return $this->userEmail . ", " . $this->pwdEmail . ", " . $this->smtpEmail . ", " .  $this->portSmtpEmail; 
    }
}
