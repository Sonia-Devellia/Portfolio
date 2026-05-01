<?php
$base       = rtrim($_ENV['APP_URL'] ?? '', '/');
/** @var callable(string): string $t */
$t          ??= static fn(string $k): string => $k;
/** @var bool $success */
$success    ??= false;
/** @var bool $error */
$error      ??= false;
/** @var string $csrf_token */
$csrf_token ??= '';
?>
<section class="contact-page">
    <div class="contact-page__inner">

        <!-- Colonne info -->
        <div class="contact-page__info">
            <p class="eyebrow"><?= $t('contact.eyebrow') ?></p>
            <h1 class="contact-page__title"><?= $t('contact.title') ?></h1>
            <div class="contact-page__avail">
                <span class="contact-page__avail-dot"></span>
                <?= $t('contact.available') ?>
            </div>
            <p class="contact-page__sub"><?= $t('contact.sub') ?></p>
            <ul class="contact-page__socials" aria-label="<?= $t('contact.socials.aria') ?>">
                <li>
                    <a href="https://www.linkedin.com/in/sonia-habibi"
                       class="contact-page__social-link"
                       target="_blank" rel="noopener"
                       aria-label="LinkedIn <?= $t('contact.social.new_tab') ?>">
                        LinkedIn ↗
                    </a>
                </li>
                <li>
                    <a href="https://github.com/sonia-habibi"
                       class="contact-page__social-link"
                       target="_blank" rel="noopener"
                       aria-label="GitHub <?= $t('contact.social.new_tab') ?>">
                        GitHub ↗
                    </a>
                </li>
                <li>
                    <a href="https://www.malt.fr/profile/soniahabibi"
                       class="contact-page__social-link"
                       target="_blank" rel="noopener"
                       aria-label="Malt <?= $t('contact.social.new_tab') ?>">
                        Malt ↗
                    </a>
                </li>
            </ul>
        </div>

        <!-- Colonne formulaire -->
        <div class="contact-page__form-wrap">

            <?php if ($success): ?>
                <div class="alert alert--success" role="alert" aria-live="polite">
                    <?= $t('contact.success') ?>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert--error" role="alert" aria-live="assertive">
                    <?= $t('contact.error') ?>
                </div>
            <?php endif; ?>

            <form class="contact-form" action="<?= $base ?>/contact" method="post" novalidate>
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($csrf_token) ?>">

                <div class="form-group">
                    <label for="name"><?= $t('contact.name') ?></label>
                    <input type="text" id="name" name="name"
                           required aria-required="true" autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="email"><?= $t('contact.email') ?></label>
                    <input type="email" id="email" name="email"
                           required aria-required="true" autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="message"><?= $t('contact.message') ?></label>
                    <textarea id="message" name="message" rows="6"
                              required aria-required="true"></textarea>
                </div>

                <button type="submit" class="btn btn--dark"><?= $t('contact.send') ?></button>
            </form>

        </div>

    </div><!-- /.contact-page__inner -->
</section>
