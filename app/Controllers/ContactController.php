<?php

namespace App\Controllers;

use Core\Controller;

class ContactController extends Controller
{
    public function index(): void
    {
         if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

        $this->render('contact/index', [
            'title' => 'Contact — Sonia Habibi',
        ]);
    }

    public function send(): void
    {
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation basique
        if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['contact_error'] = true;
            $this->redirect('/contact');
            return;
        }

        // Protection CSRF basique
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['contact_error'] = true;
            $this->redirect('/contact');
            return;
        }

        $to      = $_ENV['MAIL_TO'] ?? '';
        $subject = "Portfolio — Message de {$name}";
        $body    = "Nom : {$name}\nEmail : {$email}\n\nMessage :\n{$message}";
        $headers = "From: {$_ENV['MAIL_FROM']}\r\nReply-To: {$email}\r\nContent-Type: text/plain; charset=UTF-8";

        $sent = mail($to, $subject, $body, $headers);

        if ($sent) {
            $_SESSION['contact_success'] = true;
        } else {
            $_SESSION['contact_error'] = true;
        }

        $this->redirect('/contact');
    }
}
