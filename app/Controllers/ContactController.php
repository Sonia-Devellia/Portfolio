<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use App\Helpers\Logger;
use App\Helpers\Mailer;

class ContactController extends Controller
{
    private const MIN_MESSAGE_LEN = 10;
    private const MAX_MESSAGE_LEN = 5000;
    private const MAX_NAME_LEN    = 120;
    private const MAX_EMAIL_LEN   = 254;

    /** Anti-spam : minimum entre l'affichage du formulaire et l'envoi. */
    private const MIN_FORM_DELAY_S = 3;

    /** Anti-spam : intervalle minimum entre deux envois depuis la même session. */
    private const SUBMIT_COOLDOWN_S = 60;

    public function index(): void
    {
        $_SESSION['contact_form_loaded_at'] = time();

        $success = !empty($_SESSION['contact_success']);
        $error   = !empty($_SESSION['contact_error']);
        unset($_SESSION['contact_success'], $_SESSION['contact_error']);

        $lang     = $_SESSION['lang'] ?? 'fr';
        $metaDesc = $lang === 'fr'
            ? 'Discutons de votre projet. Développeuse freelance PHP, Python, IA — disponible en remote. Je réponds sous 24h.'
            : 'Let\'s talk about your project. Freelance PHP, Python, AI developer — available remotely. I reply within 24h.';

        $this->render('contact/index', [
            'title'      => 'Contact · Sonia Habibi',
            'metaDesc'   => $metaDesc,
            'canonical'  => base_url() . '/contact',
            'csrf_token' => csrf_token(),
            'success'    => $success,
            'error'      => $error,
        ]);
    }

    public function send(): void
    {
        // 1. CSRF
        if (!csrf_check()) {
            Logger::security('contact_csrf_rejected');
            $this->fail(); return;
        }

        // 2. Honeypot : champ caché que seuls les bots remplissent
        if (!empty($_POST['website'])) {
            Logger::security('contact_honeypot_triggered');
            // On feinte le succès pour ne pas signaler le piège aux bots
            $_SESSION['contact_success'] = true;
            $this->redirect('/contact'); return;
        }

        // 3. Timing : un humain met >3s à remplir, un bot envoie en <1s
        $loadedAt = $_SESSION['contact_form_loaded_at'] ?? 0;
        if ($loadedAt > 0 && (time() - $loadedAt) < self::MIN_FORM_DELAY_S) {
            Logger::security('contact_too_fast', ['elapsed' => time() - $loadedAt]);
            $this->fail(); return;
        }

        // 4. Cooldown : une soumission max par minute par session
        $lastSubmit = $_SESSION['contact_last_submit'] ?? 0;
        if (time() - $lastSubmit < self::SUBMIT_COOLDOWN_S) {
            Logger::security('contact_cooldown', ['since' => time() - $lastSubmit]);
            $this->fail(); return;
        }

        // 5. Validation
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $message = trim($_POST['message'] ?? '');
        $type    = $this->validProjectType($_POST['project_type'] ?? '');

        if (!$this->validInput($name, $email, $message)) {
            $this->fail(); return;
        }

        // 6. Anti-CRLF (header injection)
        if ($this->hasHeaderInjection($name) || $this->hasHeaderInjection($email)) {
            Logger::security('contact_header_injection_attempt', [
                'name'  => mb_substr($name, 0, 80),
                'email' => mb_substr($email, 0, 80),
            ]);
            $this->fail(); return;
        }

        // 7. Envoi
        $_SESSION['contact_last_submit'] = time();

        if ($this->sendMail($name, $email, $message, $type)) {
            Logger::info('contact_sent', [
                'email_domain' => substr((string) strrchr($email, '@'), 1),
                'message_len'  => mb_strlen($message),
                'project_type' => $type,
            ]);
            $_SESSION['contact_success'] = true;
        } else {
            Logger::error('contact_mail_failed');
            $_SESSION['contact_error'] = true;
        }

        $this->redirect('/contact');
    }

    // ─── Helpers privés ──────────────────────────────────────────────────

    private function validInput(string $name, string $email, string $message): bool
    {
        if ($name === '' || $email === '' || $message === '')        return false;
        if (mb_strlen($name)    > self::MAX_NAME_LEN)                 return false;
        if (mb_strlen($email)   > self::MAX_EMAIL_LEN)                return false;
        if (mb_strlen($message) < self::MIN_MESSAGE_LEN)              return false;
        if (mb_strlen($message) > self::MAX_MESSAGE_LEN)              return false;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))               return false;
        return true;
    }

    private function validProjectType(string $type): string
    {
        return in_array($type, ['site', 'app', 'ai', 'other'], true) ? $type : 'other';
    }

    private function hasHeaderInjection(string $value): bool
    {
        return preg_match('/[\r\n\0]/', $value) === 1;
    }

    private function sendMail(string $name, string $email, string $message, string $type): bool
    {
        $to = $_ENV['MAIL_TO'] ?? '';
        if ($to === '') return false;

        $subject = "Portfolio — Message de {$name}";

        $body = "Nom : {$name}\n"
              . "Email : {$email}\n"
              . "Type : {$type}\n\n"
              . "Message :\n{$message}\n";

        return Mailer::send($to, $subject, $body, replyTo: $email, fromName: 'Portfolio Sonia Habibi');
    }

    private function fail(): void
    {
        $_SESSION['contact_error'] = true;
        $this->redirect('/contact');
    }
}
