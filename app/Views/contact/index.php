<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$success = !empty($_SESSION['contact_success']);
$error   = !empty($_SESSION['contact_error']);
unset($_SESSION['contact_success'], $_SESSION['contact_error']);
?>

<section class="section contact-page">
    <div class="contact-page__inner">

        <div class="contact-page__header">
            <p class="eyebrow"><?= $t('contact.eyebrow') ?></p>
            <h1 class="section__title"><?= $t('contact.title') ?></h1>
            <p class="contact-page__sub"><?= $t('contact.sub') ?></p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert--success"><?= $t('contact.success') ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert--error"><?= $t('contact.error') ?></div>
        <?php endif; ?>

        <form class="contact-form" action="<?= url('/contact') ?>" method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <div class="form-group">
                <label for="name"><?= $t('contact.name') ?></label>
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email"><?= $t('contact.email') ?></label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="message"><?= $t('contact.message') ?></label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn--dark"><?= $t('contact.send') ?></button>
        </form>

    </div>
</section>
