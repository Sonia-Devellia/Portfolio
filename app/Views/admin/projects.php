<?php
/**
 * Vue admin — liste des projets. FR-only par choix produit.
 *
 * @var array<int, array<string, mixed>> $projects  Liste des projets en BDD
 * @var string                            $title    Titre de la page (utilisé par le layout)
 */
$base     = rtrim($_ENV['APP_URL'] ?? '', '/');
$projects ??= [];
$title    ??= 'Admin — Projets';
?>
<div class="admin-content">

    <div class="admin-content__header">
        <h1 class="admin-content__title">Projets</h1>
        <a href="<?= $base ?>/admin/projets/new" class="btn btn--dark btn--sm">Ajouter →</a>
    </div>

    <?php if (empty($projects)): ?>
        <div class="admin-empty">
            <p>Aucun projet pour l'instant.</p>
        </div>
    <?php else: ?>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th scope="col">Ordre</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Tags</th>
                    <th scope="col">Featured</th>
                    <th scope="col">WIP</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td class="admin-table__order"><?= (int) $project['sort_order'] ?></td>
                    <td class="admin-table__title"><?= htmlspecialchars($project['title_fr']) ?></td>
                    <td><?= htmlspecialchars($project['tags']) ?></td>
                    <td class="admin-table__check"><?= $project['is_featured'] ? '✓' : '' ?></td>
                    <td class="admin-table__check"><?= $project['is_wip']      ? '✓' : '' ?></td>
                    <td>
                        <div class="admin-table__actions">
                            <a href="<?= $base ?>/admin/projets/<?= (int) $project['id'] ?>"
                               class="btn btn--outline btn--sm">Modifier</a>
                            <form method="post"
                                  action="<?= $base ?>/admin/projets/<?= (int) $project['id'] ?>/delete">
                                <input type="hidden" name="csrf_token"
                                       value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <button type="submit"
                                        class="btn btn--outline btn--sm admin-table__delete"
                                        data-confirm="Supprimer « <?= htmlspecialchars($project['title_fr'], ENT_QUOTES) ?> » ?">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>
</div>
