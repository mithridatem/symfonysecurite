<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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


    public function sendEmail(string $destinataire, string $objet, string $body) {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->smtpEmail;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->userEmail;                     //SMTP username
            $mail->Password   = $this->pwdEmail;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $this->portSmtpEmail;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($this->userEmail, 'Mailer');
            $mail->addAddress($destinataire);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $objet;
            $mail->Body    = $body;
            //Envoi le mail
            $mail->send();
           return 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

