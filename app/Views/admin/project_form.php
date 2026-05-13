<?php
/**
 * Vue admin — formulaire création / édition d'un projet.
 *
 * @var array<string, mixed>|null $project  Projet à éditer, ou null pour création
 */
$base    = base_url();
$project ??= null;
$isEdit  = $project !== null;
$action  = $isEdit ? "{$base}/admin/projets/" . (int) $project['id'] : "{$base}/admin/projets/new";
$val     = static fn(string $k): string => e((string) ($project[$k] ?? ''));
?>

<div class="admin-content">

    <div class="admin-content__header">
        <h1 class="admin-content__title"><?= $isEdit ? 'Modifier le projet' : 'Nouveau projet' ?></h1>
        <a href="<?= $base ?>/admin/projets" class="btn btn--outline btn--sm">← Retour</a>
    </div>

    <div class="admin-form-card">
        <form class="admin-form" method="post" action="<?= $action ?>" novalidate>
            <?= csrf_field() ?>

            <div class="admin-form__section">
                <span class="admin-form__section-title">Contenu — Français</span>
                <div class="form-group">
                    <label for="title_fr">Titre</label>
                    <input type="text" id="title_fr" name="title_fr"
                           value="<?= $val('title_fr') ?>" required aria-required="true">
                </div>
                <div class="form-group">
                    <label for="desc_fr">Description</label>
                    <textarea id="desc_fr" name="desc_fr" rows="4"><?= $val('desc_fr') ?></textarea>
                </div>
            </div>

            <div class="admin-form__section">
                <span class="admin-form__section-title">Contenu — English</span>
                <div class="form-group">
                    <label for="title_en">Title</label>
                    <input type="text" id="title_en" name="title_en"
                           value="<?= $val('title_en') ?>" required aria-required="true">
                </div>
                <div class="form-group">
                    <label for="desc_en">Description</label>
                    <textarea id="desc_en" name="desc_en" rows="4"><?= $val('desc_en') ?></textarea>
                </div>
            </div>

            <div class="admin-form__section">
                <span class="admin-form__section-title">Métadonnées</span>
                <div class="admin-form__row">
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" name="slug"
                               value="<?= $val('slug') ?>" required aria-required="true" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags <small>(virgule-séparés)</small></label>
                        <input type="text" id="tags" name="tags" value="<?= $val('tags') ?>">
                    </div>
                </div>
                <div class="admin-form__row">
                    <div class="form-group">
                        <label for="github_url">GitHub URL</label>
                        <input type="url" id="github_url" name="github_url" value="<?= $val('github_url') ?>">
                    </div>
                    <div class="form-group">
                        <label for="demo_url">Demo URL</label>
                        <input type="url" id="demo_url" name="demo_url" value="<?= $val('demo_url') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="thumbnail">Thumbnail <small>(chemin relatif)</small></label>
                    <input type="text" id="thumbnail" name="thumbnail"
                           value="<?= $val('thumbnail') ?>"
                           placeholder="/assets/images/projets/mon-projet.webp">
                </div>
            </div>

            <div class="admin-form__section">
                <span class="admin-form__section-title">Options</span>
                <div class="admin-form__options">
                    <label class="form-check">
                        <input type="checkbox" name="is_featured" value="1"
                               <?= !empty($project['is_featured']) ? 'checked' : '' ?>>
                        Featured (accueil)
                    </label>
                    <label class="form-check">
                        <input type="checkbox" name="is_wip" value="1"
                               <?= !empty($project['is_wip']) ? 'checked' : '' ?>>
                        Work in progress
                    </label>
                    <div class="form-group admin-form__sort">
                        <label for="sort_order">Ordre</label>
                        <input type="number" id="sort_order" name="sort_order"
                               value="<?= (int) ($project['sort_order'] ?? 0) ?>" min="0">
                    </div>
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="btn btn--dark"><?= $isEdit ? 'Enregistrer' : 'Créer le projet' ?></button>
                <a href="<?= $base ?>/admin/projets" class="btn btn--outline">Annuler</a>
            </div>

        </form>
    </div>

</div>
