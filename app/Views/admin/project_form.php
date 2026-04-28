<?php
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$isEdit = $project !== null;
$action = $isEdit ? url('/admin/projets/' . (int) $project['id']) : url('/admin/projets/new');
$val    = fn(string $k) => htmlspecialchars((string) ($project[$k] ?? ''));
?>

<div class="admin-section">

    <div class="admin-section__head">
        <h1 class="admin-section__title">
            <?= $isEdit ? 'Modifier le projet' : 'Nouveau projet' ?>
        </h1>
        <a href="<?= url('/admin/projets') ?>" class="btn btn--outline btn--sm">← Retour</a>
    </div>

    <form class="admin-form" method="post" action="<?= $action ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <!-- Titres -->
        <div class="admin-form__row">
            <div class="form-group">
                <label for="title_fr">Titre (FR)</label>
                <input type="text" id="title_fr" name="title_fr"
                       value="<?= $val('title_fr') ?>" required>
            </div>
            <div class="form-group">
                <label for="title_en">Titre (EN)</label>
                <input type="text" id="title_en" name="title_en"
                       value="<?= $val('title_en') ?>" required>
            </div>
        </div>

        <!-- Descriptions -->
        <div class="admin-form__row">
            <div class="form-group">
                <label for="desc_fr">Description (FR)</label>
                <textarea id="desc_fr" name="desc_fr" rows="4"><?= $val('desc_fr') ?></textarea>
            </div>
            <div class="form-group">
                <label for="desc_en">Description (EN)</label>
                <textarea id="desc_en" name="desc_en" rows="4"><?= $val('desc_en') ?></textarea>
            </div>
        </div>

        <!-- Slug + Tags -->
        <div class="admin-form__row">
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug"
                       value="<?= $val('slug') ?>" required>
            </div>
            <div class="form-group">
                <label for="tags">Tags <small>(séparés par virgule)</small></label>
                <input type="text" id="tags" name="tags"
                       value="<?= $val('tags') ?>">
            </div>
        </div>

        <!-- URLs -->
        <div class="admin-form__row">
            <div class="form-group">
                <label for="github_url">GitHub URL</label>
                <input type="url" id="github_url" name="github_url"
                       value="<?= $val('github_url') ?>">
            </div>
            <div class="form-group">
                <label for="demo_url">Demo URL</label>
                <input type="url" id="demo_url" name="demo_url"
                       value="<?= $val('demo_url') ?>">
            </div>
        </div>

        <!-- Thumbnail -->
        <div class="form-group">
            <label for="thumbnail">Thumbnail (chemin relatif)</label>
            <input type="text" id="thumbnail" name="thumbnail"
                   value="<?= $val('thumbnail') ?>"
                   placeholder="/assets/images/projets/mon-projet.webp">
        </div>

        <!-- Options -->
        <div class="admin-form__options">
            <label class="form-check">
                <input type="checkbox" name="is_featured" value="1"
                       <?= !empty($project['is_featured']) ? 'checked' : '' ?>>
                Featured (affiché sur l'accueil)
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_wip" value="1"
                       <?= !empty($project['is_wip']) ? 'checked' : '' ?>>
                Work in progress
            </label>
            <div class="form-group admin-form__sort">
                <label for="sort_order">Ordre</label>
                <input type="number" id="sort_order" name="sort_order"
                       value="<?= (int) ($project['sort_order'] ?? 0) ?>"
                       min="0">
            </div>
        </div>

        <div class="admin-form__actions">
            <button type="submit" class="btn btn--dark">
                <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le projet' ?>
            </button>
        </div>
    </form>

</div>
