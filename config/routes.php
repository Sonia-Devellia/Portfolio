<?php

// ─── Public ───────────────────────────────────────────────
$router->get('/',                    'HomeController',    'index');
$router->get('/projets',             'ProjectController', 'index');
$router->get('/projets/{slug}',      'ProjectController', 'show');
$router->get('/contact',             'ContactController', 'index');
$router->post('/contact',            'ContactController', 'send');

// ─── Langue ───────────────────────────────────────────────
$router->get('/lang/{code}',         'LangController',    'switch');

// ─── Admin auth ───────────────────────────────────────────
$router->get('/admin/login',                    'AdminController', 'login');
$router->post('/admin/login',                   'AdminController', 'loginPost');
$router->get('/admin/logout',                   'AdminController', 'logout');

// ─── Admin ────────────────────────────────────────────────
$router->get('/admin',                          'AdminController', 'index');
$router->get('/admin/projets',                  'AdminController', 'projects');
$router->get('/admin/projets/new',              'AdminController', 'newProject');
$router->post('/admin/projets/new',             'AdminController', 'createProject');
$router->get('/admin/projets/{id}',             'AdminController', 'editProject');
$router->post('/admin/projets/{id}',            'AdminController', 'updateProject');
$router->post('/admin/projets/{id}/delete',     'AdminController', 'deleteProject');
