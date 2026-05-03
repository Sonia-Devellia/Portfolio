<?php
/**
 * Vue — liste de tous les projets.
 *
 * @var callable(string): string                  $t         Helper de traduction
 * @var array<int, array<string, mixed>>          $projects  Liste des projets
 */
$base = rtrim($_ENV['APP_URL'] ?? '', '/');
$t ??= static fn(string $k): string => $k;
$projects ??= [];

if (!function_exists('tagColor')) {
    function tagColor(string $tag): string {
        $tag = strtolower($tag);
        if (str_contains($tag, 'php'))    return 'tag--blue';
        if (str_contains($tag, 'python')) return 'tag--green';
        if (str_contains($tag, 'js') || str_contains($tag, 'javascript')) return 'tag--amber';
        if (str_contains($tag, 'ia') || str_contains($tag, 'llm') || str_contains($tag, 'ai') || str_contains($tag, 'claude') || str_contains($tag, 'openai')) return 'tag--purple';
        if (str_contains($tag, 'scss') || str_contains($tag, 'css')) return 'tag--coral';
        return 'tag--gray';
    }
}
?>

<section class="section projects-page">
    <div class="section__head">
        <div>
            <p class="eyebrow"><?= $t('projects.eyebrow') ?></p>
            <h1 class="section__title"><?= $t('projects.all') ?></h1>
        </div>
    </div>

    <div class="projects__grid">
        <?php foreach ($projects as $project):
            $lang  = $_SESSION['lang'] ?? 'fr';
            $title = htmlspecialchars($project['title_' . $lang]);
            $tags  = \App\Models\Project::parseTags($project['tags']);
        ?>
        <article class="project-card <?= $project['is_wip'] ? 'project-card--wip' : '' ?>" data-theme="dark">
            <?php if ($project['is_wip']): ?>
                <div class="project-card__thumb project-card__thumb--empty">
                    <span class="project-card__wip-label">+ IA</span>
                </div>
            <?php elseif ($project['thumbnail']): ?>
                <div class="project-card__thumb">
                    <img src="<?= htmlspecialchars($project['thumbnail']) ?>"
                         alt="<?= $title ?>"
                         width="560" height="320"
                         loading="lazy"
                         decoding="async">
                </div>
            <?php else: ?>
                <div class="project-card__thumb project-card__thumb--placeholder">
                    <span><?= strtoupper(substr($title, 0, 2)) ?></span>
                </div>
            <?php endif; ?>

            <div class="project-card__body">
                <div class="project-card__tags">
                    <?php foreach ($tags as $tag): ?>
                        <span class="tag <?= tagColor($tag) ?>"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                <h2 class="project-card__title"><?= $title ?></h2>
                <div class="project-card__links">
                    <?php if ($project['is_wip']): ?>
                        <span class="project-card__link project-card__link--muted"><?= $t('projects.wip') ?></span>
                    <?php elseif ($project['github_url']): ?>
                        <a href="<?= htmlspecialchars($project['github_url']) ?>"
                           class="project-card__link"
                           target="_blank" rel="noopener"
                           aria-label="<?= $title ?> GitHub (<?= $t('contact.social.new_tab') ?>)">GitHub ↗</a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
