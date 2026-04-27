<?php

namespace App\Controllers;

use Core\Controller;

class LangController extends Controller
{
    public function switch(string $code): void
    {
        $allowed = ['fr', 'en'];

        if (in_array($code, $allowed, true)) {
            $_SESSION['lang'] = $code;
        }

        // Retour à la page précédente ou à l'accueil
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referer);
        exit;
    }
}
