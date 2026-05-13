<?php

// ─── Public ───────────────────────────────────────────────
$router->get('/',                    'HomeController',    'index');
$router->get('/projets',             'ProjectController', 'index');
$router->get('/projects',            'ProjectController', 'index');
$router->get('/case-studies/triage-support', 'CaseStudyController', 'triage');
$router->get('/case-studies/amanea-voyages', 'CaseStudyController', 'amanea');
$router->get('/contact',             'ContactController', 'index');
$router->post('/contact',            'ContactController', 'send');

// ─── Tarifs ───────────────────────────────────────────────
$router->get('/tarifs',              'TarifsController',  'index');

// ─── Pages géographiques ──────────────────────────────────
$router->get('/dev-freelance/{slug}', 'GeoController',   'show');

// ─── SEO ──────────────────────────────────────────────────
// Le robots.txt reste un fichier statique dans public/ (servi directement par Apache).
// Le sitemap.xml est forcé en dynamique via .htaccess RewriteRule.
$router->get('/sitemap.xml',          'SitemapController', 'index');

// ─── Langue ───────────────────────────────────────────────
$router->get('/lang/{code}',         'LangController',    'switch');

// ─── Admin auth ───────────────────────────────────────────
$router->get('/admin/login',                    'AdminController', 'login');
$router->post('/admin/login',                   'AdminController', 'loginPost');
$router->post('/admin/logout',                  'AdminController', 'logout');

// ─── Admin ────────────────────────────────────────────────
$router->get('/admin',                          'AdminController', 'index');
$router->get('/admin/projets',                  'AdminController', 'projects');
$router->get('/admin/projets/new',              'AdminController', 'newProject');
$router->post('/admin/projets/new',             'AdminController', 'createProject');
$router->get('/admin/projets/{id}',             'AdminController', 'editProject');
$router->post('/admin/projets/{id}',            'AdminController', 'updateProject');
$router->post('/admin/projets/{id}/delete',     'AdminController', 'deleteProject');
