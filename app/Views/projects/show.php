<?php
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
$lang  = $_SESSION['lang'] ?? 'fr';
$pTitle = htmlspecialchars($project['title_' . $lang]);
$pDesc  = htmlspecialchars($project['desc_' . $lang]);
$tags   = \App\Models\Project::parseTags($project['tags']);
?>

<section class="section project-detail">

    <a href="<?= url('/projets') ?>" class="project-detail__back"><?= $t('project.back') ?></a>

    <div class="project-detail__header">
        <div class="project-detail__tags">
            <?php foreach ($tags as $tag): ?>
                <span class="tag <?= tagColor($tag) ?>"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
        </div>
        <h1 class="project-detail__title"><?= $pTitle ?></h1>
        <p class="project-detail__desc"><?= $pDesc ?></p>
        <div class="project-detail__links">
            <?php if ($project['github_url']): ?>
                <a href="<?= htmlspecialchars($project['github_url']) ?>"
                   class="btn btn--outline"
                   target="_blank" rel="noopener"><?= $t('projects.github') ?></a>
            <?php endif; ?>
            <?php if ($project['demo_url']): ?>
                <a href="<?= htmlspecialchars($project['demo_url']) ?>"
                   class="btn btn--dark"
                   target="_blank" rel="noopener"><?= $t('project.demo') ?></a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($project['thumbnail']): ?>
    <div class="project-detail__thumb">
        <img src="<?= htmlspecialchars($project['thumbnail']) ?>"
             alt="<?= $pTitle ?>"
             loading="lazy">
    </div>
    <?php endif; ?>

</section>
