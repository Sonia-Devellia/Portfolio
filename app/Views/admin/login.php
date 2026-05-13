<?php
/**
 * Vue admin — formulaire de connexion (FR-only par choix produit).
 *
 * @var string|null $error  Code d'erreur : 'credentials' | 'csrf' | 'rate_limit' | null
 */
$error ??= null;

$messages = [
    'credentials' => 'Identifiants incorrects.',
    'csrf'        => 'Session expirée, veuillez réessayer.',
    'rate_limit'  => 'Trop de tentatives. Réessayez dans quelques minutes.',
];
?>
<div class="login-page">
    <div class="login-card">

        <span class="login-card__logo">Sonia</span>
        <span class="login-card__subtitle">Espace administration</span>

        <?php if (isset($messages[$error])): ?>
            <div class="alert alert--error" role="alert"><?= $messages[$error] ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url() ?>/admin/login" novalidate>
            <?= csrf_field() ?>

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
