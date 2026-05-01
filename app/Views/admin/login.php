<?php
/**
 * Vue admin — FR-only par choix produit.
 *
 * @var string|null $error  Code d'erreur affiché : 'credentials' | 'csrf' | null
 */
$base  = rtrim($_ENV['APP_URL'] ?? '', '/');
$error ??= null;
?>
<div class="login-page">
    <div class="login-card">

        <span class="login-card__logo">Sonia</span>
        <span class="login-card__subtitle">Espace administration</span>

        <?php if ($error === 'credentials'): ?>
            <div class="alert alert--error" role="alert">Identifiants incorrects.</div>
        <?php elseif ($error === 'csrf'): ?>
            <div class="alert alert--error" role="alert">Session expirée, veuillez réessayer.</div>
        <?php elseif ($error === 'rate_limit'): ?>
            <div class="alert alert--error" role="alert">Trop de tentatives. Réessayez dans quelques minutes.</div>
        <?php endif; ?>

        <form method="post" action="<?= $base ?>/admin/login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username"
                       required aria-required="true" autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"
                       required aria-required="true" autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn--dark">Connexion</button>
        </form>

    </div>
</div>
