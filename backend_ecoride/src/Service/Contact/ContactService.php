<?php

namespace App\Service\Contact;

use App\DTO\Contact\ContactRequestDTO;
use App\DTO\Mail\{
    MailAddressDTO,
    MailHeaderDTO,
    MailMessageDTO,
};

use App\Service\Mail\ContactConfirmationMailTemplate;
use App\Service\Mcs\McsMailService;
use App\Service\ValidationService;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class ContactService
{
    public function __construct(
        private McsMailService $mcsMailService,
        private ContactConfirmationMailTemplate $contactConfirmationMailTemplate,
        private ValidationService $validationService,
        private string $mcsContactFromEmail,
        private string $mcsContactFromName,
        private string $mcsContactToEmail,
        private string $mcsContactToName,
        private string $mcsContactReturnPath,
    ) {}

    public function send(ContactRequestDTO $contactRequestDTO): void
    {
        if ($contactRequestDTO->isEmpty()) {
            throw new BadRequestHttpException('No data provided.');
        }

        $this->validationService->validate($contactRequestDTO, ['create']);

        $name = trim((string) $contactRequestDTO->name);
        $email = trim((string) $contactRequestDTO->email);
        $message = trim((string) $contactRequestDTO->message);

        $this->sendInternalNotification($name, $email, $message);
        $this->sendUserConfirmation($name, $email, $message);
    }

    private function sendInternalNotification(string $name, string $email, string $message): void
    {
        $subject = sprintf('Nouveau message de contact - %s', $name);

        $textBody = <<<TEXT
Nouveau message reçu depuis le formulaire de contact EcoRide.

Nom : {$name}
Email : {$email}

Message :
{$message}
TEXT;

    $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $safeEmail = htmlspecialchars($email, ENT_QUOTES - ENT_SUBSTITUTE, 'UTF-8');
    $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

    $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau message de contact</title>
</head>
<body style="margin:0; padding:24px; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; margin:0 auto; background-color:#ffffff; border-radius:12px; overflow:hidden;">
        <tr>
            <td style="background-color:#16a34a; padding:24px; color:#ffffff; text-align:center;">
                <h1 style="margin:0; font-size:22px;">EcoRide</h1>
                <p style="margin:8px 0 0 0; font-size:14px;">Nouveau message de contact</p>
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 12px 0;"><strong>Nom :</strong> {$safeName}</p>
                <p style="margin:0 0 12px 0;"><strong>Email :</strong> {$safeEmail}</p>
                <div style="margin-top:20px; padding:16px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px;">
                    <p style="margin:0 0 8px 0;"><strong>Message :</strong></p>
                    <p style="margin:0; line-height:1.6;">{$safeMessage}</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;

        $mail = new MailMessageDTO(
            from: new MailAddressDTO($this->mcsContactFromEmail, $this->mcsContactFromName),
            to: [
                new MailAddressDTO($this->mcsContactToEmail, $this->mcsContactToName),
            ],
            subject: $subject,
            textBody: $textBody,
            htmlBody: $htmlBody,
            headers: [
                new MailHeaderDTO('X-App-ID', 'EcoRide'),
            ],
            replyTo: [
                new MailAddressDTO($email, $name),
            ],
            returnPath: $this->mcsContactReturnPath,
        );

        $this->mcsMailService->send($mail);
    }

    private function sendUserConfirmation(string $name, string $email, string $message): void
    {
        $subject = 'EcoRide - Confirmation de réception de votre message';

        $textBody = $this->contactConfirmationMailTemplate->renderText($name);
        $htmlBody = $this->contactConfirmationMailTemplate->render($name, $message);

        $mail = new MailMessageDTO(
            from: new MailAddressDTO($this->mcsContactFromEmail, $this->mcsContactFromName),
            to: [
                new MailAddressDTO($email, $name),
            ],
            subject: $subject,
            textBody: $textBody,
            htmlBody: $htmlBody,
            headers: [
                new MailHeaderDTO('X-App-ID', 'EcoRide'),
            ],
            returnPath: $this->mcsContactReturnPath,
        );

        $this->mcsMailService->send($mail);
    }
}


?>