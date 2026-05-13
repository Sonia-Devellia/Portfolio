<?php

/**
 * Stack technique — 3 familles d'outils utilisés en production.
 * Consommé par app/Views/home/index.php (section Stack).
 */

return [
    [
        'role_key'  => 'stack.role.backend',
        'title_key' => 'stack.card.backend.title',
        'sub_key'   => 'stack.card.backend.sub',
        'foot_key'  => 'stack.card.backend.foot',
        'side'      => 'left',
        'techs'     => [
            ['name' => 'PHP · MVC natif',            'meta' => '4 ans',     'lvl' => 90],
            ['name' => 'Python · scripts · API',     'meta' => '2 ans',     'lvl' => 70],
            ['name' => 'MySQL · PostgreSQL',         'meta' => '3 ans',     'lvl' => 80],
            ['name' => 'API REST · auth · paiement', 'meta' => 'quotidien', 'lvl' => 85],
        ],
    ],
    [
        'role_key'  => 'stack.role.frontend',
        'title_key' => 'stack.card.frontend.title',
        'sub_key'   => 'stack.card.frontend.sub',
        'foot_key'  => 'stack.card.frontend.foot',
        'side'      => 'right',
        'techs'     => [
            ['name' => 'HTML · CSS · SCSS',         'meta' => '4 ans',     'lvl' => 95],
            ['name' => 'JavaScript vanilla',        'meta' => '3 ans',     'lvl' => 85],
            ['name' => 'React · Next.js',           'meta' => '2 ans',     'lvl' => 70],
            ['name' => 'Design system · Tailwind',  'meta' => 'quotidien', 'lvl' => 80],
        ],
    ],
    [
        'role_key'  => 'stack.role.tooling',
        'title_key' => 'stack.card.tooling.title',
        'sub_key'   => 'stack.card.tooling.sub',
        'foot_key'  => 'stack.card.tooling.foot',
        'side'      => 'left',
        'techs'     => [
            ['name' => 'Git · GitHub · CI/CD',      'meta' => 'quotidien', 'lvl' => 90],
            ['name' => 'Apache · VPS · o2switch',   'meta' => '2 ans',     'lvl' => 70],
            ['name' => 'Claude Code · Cursor (IA)', 'meta' => 'quotidien', 'lvl' => 85],
            ['name' => 'Tests · monitoring · logs', 'meta' => 'en cours',  'lvl' => 60],
        ],
    ],
];
