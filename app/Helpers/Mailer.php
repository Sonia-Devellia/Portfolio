<?php

declare(strict_types=1);

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envoi d'emails — SMTP authentifié si configuré, fallback mail() natif sinon.
 *
 * Variables d'environnement attendues en prod :
 *   MAIL_HOST      ssl0.ovh.net (OVH) — laisser vide pour forcer le fallback mail()
 *   MAIL_PORT      465 (SSL) ou 587 (STARTTLS)
 *   MAIL_USER      email complet : ex. contact@sonia-habibi.dev
 *   MAIL_PASS      mot de passe du compte mail OVH
 *   MAIL_FROM      adresse d'envoi (doit appartenir au domaine OVH pour SPF/DKIM)
 *   MAIL_TO        adresse de réception
 *
 * Pourquoi SMTP plutôt que mail() en prod : DKIM signé automatiquement par OVH,
 * SPF aligné, meilleure deliverability (pas de spam folder), retours d'erreur précis.
 */
class Mailer
{
    public static function send(
        string $to,
        string $subject,
        string $body,
        ?string $replyTo = null,
        ?string $fromName = null,
    ): bool {
        $host = $_ENV['MAIL_HOST'] ?? '';
        $user = $_ENV['MAIL_USER'] ?? '';
        $pass = $_ENV['MAIL_PASS'] ?? '';
        $from = $_ENV['MAIL_FROM'] ?? 'contact@sonia-habibi.dev';

        // SMTP non configuré ou PHPMailer absent → fallback mail() natif
        if ($host === '' || $user === '' || $pass === '' || !class_exists(PHPMailer::class)) {
            return self::sendNative($to, $subject, $body, $from, $replyTo);
        }

        return self::sendSmtp($to, $subject, $body, $from, $fromName ?? 'Portfolio', $replyTo, $host, $user, $pass);
    }

    // ─── SMTP authentifié (production) ────────────────────────────────────

    private static function sendSmtp(
        string $to,
        string $subject,
        string $body,
        string $from,
        string $fromName,
        ?string $replyTo,
        string $host,
        string $user,
        string $pass,
    ): bool {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->Port       = (int) ($_ENV['MAIL_PORT'] ?? 465);
            $mail->SMTPAuth   = true;
            $mail->Username   = $user;
            $mail->Password   = $pass;
            $mail->SMTPSecure = $mail->Port === 587
                ? PHPMailer::ENCRYPTION_STARTTLS
                : PHPMailer::ENCRYPTION_SMTPS;
            $mail->CharSet    = 'UTF-8';
            $mail->Timeout    = 10;

            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);
            if ($replyTo !== null && $replyTo !== '') {
                $mail->addReplyTo($replyTo);
            }

            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->isHTML(false);

            return $mail->send();
        } catch (Exception $e) {
            Logger::error('mail_smtp_failed', [
                'error' => $e->getMessage(),
                'host'  => $host,
            ]);
            return false;
        }
    }

    // ─── mail() natif (fallback local / dev) ──────────────────────────────

    private static function sendNative(
        string $to,
        string $subject,
        string $body,
        string $from,
        ?string $replyTo,
    ): bool {
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headerLines = [
            "From: {$from}",
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: Portfolio-Form',
        ];
        if ($replyTo !== null && $replyTo !== '') {
            $headerLines[] = "Reply-To: {$replyTo}";
        }
        $headers = implode("\r\n", $headerLines);

        return @mail($to, $encodedSubject, $body, $headers);
    }
}
