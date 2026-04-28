<div class="admin-section">

    <div class="admin-section__head">
        <h1 class="admin-section__title">Projets</h1>
        <a href="<?= url('/admin/projets/new') ?>" class="btn btn--dark btn--sm">+ Nouveau projet</a>
    </div>

    <?php if (empty($projects)): ?>
        <p class="admin-empty">Aucun projet pour l'instant.</p>
    <?php else: ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Slug</th>
                <th>Tags</th>
                <th>Ordre</th>
                <th>Featured</th>
                <th>WIP</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td><?= htmlspecialchars($project['title_fr']) ?></td>
                <td class="admin-table__slug"><?= htmlspecialchars($project['slug']) ?></td>
                <td><?= htmlspecialchars($project['tags']) ?></td>
                <td><?= (int) $project['sort_order'] ?></td>
                <td><?= $project['is_featured'] ? '&#10003;' : '' ?></td>
                <td><?= $project['is_wip']      ? '&#10003;' : '' ?></td>
                <td class="admin-table__actions">
                    <a href="<?= url('/admin/projets/' . $project['id']) ?>"
                       class="btn btn--outline btn--sm">Modifier</a>
                    <form method="post"
                          action="<?= url('/admin/projets/' . $project['id'] . '/delete') ?>"
                          onsubmit="return confirm('Supprimer « <?= htmlspecialchars($project['title_fr'], ENT_QUOTES) ?> » ?')">
                        <input type="hidden" name="csrf_token"
                               value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <button type="submit" class="btn btn--outline btn--sm admin-table__delete">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>
