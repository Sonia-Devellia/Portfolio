<div class="admin-login">
    <div class="admin-login__box">

        <h1 class="admin-login__title">Administration</h1>

        <?php if ($error === 'credentials'): ?>
            <div class="alert alert--error">Identifiants incorrects.</div>
        <?php elseif ($error === 'csrf'): ?>
            <div class="alert alert--error">Session expirée, veuillez réessayer.</div>
        <?php endif; ?>

        <form method="post" action="<?= url('/admin/login') ?>" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn btn--dark admin-login__submit">Connexion</button>
        </form>

    </div>
</div>
