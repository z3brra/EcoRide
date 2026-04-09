<?php

namespace App\Service\Mail;

class ContactConfirmationMailTemplate
{
    public function render(string $name, string $message): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de réception - EcoRide</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f6f8; margin:0; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background-color:#16a34a; padding:24px; text-align:center; color:#ffffff;">
                            <h1 style="margin:0; font-size:24px; font-weight:bold;">EcoRide</h1>
                            <p style="margin:8px 0 0 0; font-size:14px;">Confirmation de réception de votre message</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 24px;">
                            <p style="margin:0 0 16px 0; font-size:16px;">Bonjour {$safeName},</p>

                            <p style="margin:0 0 16px 0; font-size:15px; line-height:1.6;">
                                Nous vous confirmons la bonne réception de votre message envoyé à l’équipe EcoRide.
                            </p>

                            <p style="margin:0 0 16px 0; font-size:15px; line-height:1.6;">
                                Notre équipe reviendra vers vous dès que possible.
                            </p>

                            <div style="margin:24px 0; padding:16px; background-color:#f9fafb; border:1px solid #e5e7eb; border-radius:8px;">
                                <p style="margin:0 0 8px 0; font-size:14px; font-weight:bold;">Votre message :</p>
                                <p style="margin:0; font-size:14px; line-height:1.6; color:#374151;">{$safeMessage}</p>
                            </div>

                            <p style="margin:24px 0 0 0; font-size:15px; line-height:1.6;">
                                Merci de votre confiance,<br>
                                <strong>L’équipe EcoRide</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 24px; background-color:#f9fafb; border-top:1px solid #e5e7eb; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#6b7280;">
                                Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    public function renderText(string $name): string
    {
        return <<<TEXT
Bonjour {$name},

Nous vous confirmons la bonne réception de votre message envoyé à l'équipe EcoRide.

Notre équipe reviendra vers vous dès que possible.

Merci de votre confiance,
L'équipe EcoRide
TEXT;
    }
}

?>