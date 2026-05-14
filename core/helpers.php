<?php

declare(strict_types=1);

/**
 * Helpers globaux — disponibles dans toutes les vues et controllers.
 */

if (!function_exists('e')) {
    /**
     * Échappe une chaîne pour insertion sécurisée dans du HTML.
     * Toujours préférer e() à htmlspecialchars() dans les vues.
     */
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Retourne le token CSRF de la session, en l'initialisant au besoin.
     * Toujours injecter via un input hidden, puis vérifier côté POST avec hash_equals().
     */
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Affiche l'input hidden CSRF prêt à coller dans un <form>.
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_check')) {
    /**
     * Vérifie le token CSRF d'une requête POST (timing-safe).
     */
    function csrf_check(): bool
    {
        $sent     = $_POST['csrf_token']    ?? '';
        $expected = $_SESSION['csrf_token'] ?? '';
        return $sent !== '' && $expected !== '' && hash_equals($expected, $sent);
    }
}

if (!function_exists('base_url')) {
    /**
     * URL de base de l'application, sans slash final.
     */
    function base_url(): string
    {
        return rtrim($_ENV['APP_URL'] ?? '', '/');
    }
}

if (!function_exists('picture')) {
    /**
     * Génère un <picture> avec AVIF + WebP + fallback automatique.
     *
     * Convention : pour chaque image WebP, le helper cherche les variants suivants
     * dans le même dossier (extensions remplacées) :
     *   - .avif         : version AVIF moderne (~25-30% plus légère que WebP)
     *   - @2x.webp      : Retina, optionnel
     *   - @2x.avif      : Retina AVIF, optionnel
     *
     * Si une variante n'existe pas, elle est simplement omise — pas d'erreur,
     * pas de référence cassée. Le fallback <img> sert toujours l'image d'origine.
     *
     * Exemples :
     *   <?= picture('/assets/images/sonia.webp', 'Photo de Sonia', 480, 560, [
     *       'loading' => 'eager', 'fetchpriority' => 'high', 'class' => 'hero__img'
     *   ]) ?>
     *
     * @param array<string, string|int> $attrs  Attributs supplémentaires sur le <img>
     */
    function picture(
        string $src,
        string $alt,
        ?int $width = null,
        ?int $height = null,
        array $attrs = [],
    ): string {
        $base    = base_url();
        $publicDir = ROOT_PATH . '/public';

        // Variantes potentielles à découvrir, par ordre de priorité (AVIF d'abord)
        $avif    = preg_replace('/\.(webp|png|jpe?g)$/i', '.avif',   $src);
        $avif2x  = preg_replace('/\.(webp|png|jpe?g)$/i', '@2x.avif', $src);
        $webp2x  = preg_replace('/\.webp$/i',             '@2x.webp', $src);

        $exists  = static fn(string $p): bool => is_file($publicDir . $p);

        // ─── Construction des <source> ─────────────────────────────
        $sources = [];

        if ($avif && $exists($avif)) {
            $srcset = $avif;
            if ($avif2x && $exists($avif2x)) {
                $srcset = "{$avif} 1x, {$avif2x} 2x";
            }
            $sources[] = '<source type="image/avif" srcset="' . e($base) . $srcset . '">';
        }

        // WebP : srcset 2x si dispo
        $webpSrcset = $src;
        if ($webp2x && $exists($webp2x)) {
            $webpSrcset = "{$src} 1x, {$webp2x} 2x";
        }
        if (preg_match('/\.webp$/i', $src)) {
            $sources[] = '<source type="image/webp" srcset="' . e($base) . $webpSrcset . '">';
        }

        // ─── Attributs sur le <img> ────────────────────────────────
        $attrLine = '';
        if ($width)  $attrLine .= ' width="'  . (int) $width  . '"';
        if ($height) $attrLine .= ' height="' . (int) $height . '"';
        foreach ($attrs as $k => $v) {
            $attrLine .= ' ' . e((string) $k) . '="' . e((string) $v) . '"';
        }

        $img = '<img src="' . e($base . $src) . '" alt="' . e($alt) . '"' . $attrLine . '>';

        return $sources
            ? "<picture>" . implode('', $sources) . $img . "</picture>"
            : $img;
    }
}

if (!function_exists('asset')) {
    /**
     * URL d'un asset statique avec cache busting par hash de contenu.
     *
     * Le hash est calculé une seule fois par requête grâce à un cache statique.
     * Plus stable que filemtime() : le hash change uniquement quand le contenu change,
     * pas à chaque rsync ni à chaque redéploiement.
     *
     *   <link rel="stylesheet" href="<?= asset('/assets/css/main.css') ?>">
     *   <script src="<?= asset('/assets/js/main.js') ?>" defer></script>
     */
    function asset(string $path): string
    {
        static $cache = [];

        if (!isset($cache[$path])) {
            $full = ROOT_PATH . '/public' . $path;
            $cache[$path] = is_file($full)
                ? substr(md5_file($full), 0, 8)
                : '1';
        }

        return base_url() . $path . '?v=' . $cache[$path];
    }
}
