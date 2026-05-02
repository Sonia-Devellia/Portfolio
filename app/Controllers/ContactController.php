<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use App\Helpers\Logger;

class ContactController extends Controller
{
    /** Longueurs autorisées pour le message libre. */
    private const MIN_MESSAGE_LEN = 10;
    private const MAX_MESSAGE_LEN = 5000;

    /** Longueurs autorisées pour les champs courts. */
    private const MAX_NAME_LEN  = 120;
    private const MAX_EMAIL_LEN = 254;

    public function index(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $success = !empty($_SESSION['contact_success']);
        $error   = !empty($_SESSION['contact_error']);
        unset($_SESSION['contact_success'], $_SESSION['contact_error']);

        $this->render('contact/index', [
            'title'      => 'Contact — Sonia Habibi',
            'csrf_token' => $_SESSION['csrf_token'],
            'success'    => $success,
            'error'      => $error,
        ]);
    }

    public function send(): void
    {
        // ─── 1. CSRF d'abord (timing-safe, avant toute autre logique) ─────
        if (!$this->validCsrf()) {
            Logger::security('contact_csrf_rejected');
            $this->fail();
            return;
        }

        // ─── 2. Validation des champs ─────────────────────────────────────
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$this->validInput($name, $email, $message)) {
            $this->fail();
            return;
        }

        // ─── 3. Anti-CRLF (header injection prevention) ───────────────────
        if ($this->hasHeaderInjection($name) || $this->hasHeaderInjection($email)) {
            Logger::security('contact_header_injection_attempt', [
                'name'  => mb_substr($name, 0, 80),
                'email' => mb_substr($email, 0, 80),
            ]);
            $this->fail();
            return;
        }

        // ─── 4. Envoi mail ────────────────────────────────────────────────
        $sent = $this->sendMail($name, $email, $message);

        if ($sent) {
            Logger::info('contact_sent', [
                'name'         => $name,
                'email_domain' => substr((string) strrchr($email, '@'), 1),
                'message_len'  => mb_strlen($message),
            ]);
            $_SESSION['contact_success'] = true;
        } else {
            Logger::error('contact_mail_failed');
            $_SESSION['contact_error'] = true;
        }

        $this->redirect('/contact');
    }

    // ─── Helpers privés ──────────────────────────────────────────────────

    private function validCsrf(): bool
    {
        $sent     = $_POST['csrf_token']     ?? '';
        $expected = $_SESSION['csrf_token']  ?? '';
        return $sent !== '' && $expected !== '' && hash_equals($expected, $sent);
    }

    private function validInput(string $name, string $email, string $message): bool
    {
        if ($name === '' || $email === '' || $message === '') {
            return false;
        }
        if (mb_strlen($name) > self::MAX_NAME_LEN)        return false;
        if (mb_strlen($email) > self::MAX_EMAIL_LEN)      return false;
        if (mb_strlen($message) < self::MIN_MESSAGE_LEN)  return false;
        if (mb_strlen($message) > self::MAX_MESSAGE_LEN)  return false;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))   return false;
        return true;
    }

    /**
     * Détecte CR / LF / null bytes — vecteurs d'injection de headers SMTP.
     */
    private function hasHeaderInjection(string $value): bool
    {
        return preg_match('/[\r\n\0]/', $value) === 1;
    }

    private function sendMail(string $name, string $email, string $message): bool
    {
        $to = $_ENV['MAIL_TO'] ?? '';
        if ($to === '') {
            return false;
        }

        // Sujet encodé UTF-8 (RFC 2047) pour préserver les accents
        $subject = '=?UTF-8?B?' . base64_encode("Portfolio — Message de {$name}") . '?=';

        $body = "Nom : {$name}\n"
              . "Email : {$email}\n\n"
              . "Message :\n{$message}\n";

        $from = $_ENV['MAIL_FROM'] ?? 'noreply@sonia-habibi.dev';

        $headers = implode("\r\n", [
            "From: {$from}",
            "Reply-To: {$email}",
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: Portfolio-Form',
        ]);

        return @mail($to, $subject, $body, $headers);
    }

    private function fail(): void
    {
        $_SESSION['contact_error'] = true;
        $this->redirect('/contact');
    }
}
