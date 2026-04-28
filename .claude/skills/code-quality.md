# Skill — Qualité de Code

## Contexte projet
Stack : PHP 8.1 MVC (`App\Controllers`, `App\Models`, `Core\`), SCSS → CSS vanilla,
JS vanilla dans `public/assets/js/main.js`.
Toutes les règles s'appliquent aux modifications de tout fichier du projet.

---

## 1. Clean Code

### Noms significatifs
```php
// ✗ Vague
$data = Project::getAll();
foreach ($data as $item) { ... }

// ✓ Intentionnel
$projects = Project::getAll();
foreach ($projects as $project) { ... }
```

### Responsabilité unique — une méthode = une action
Chaque méthode de controller fait exactement une chose : guard → lire → rendre.
```php
// ✓ Correct (AdminController pattern)
public function editProject(string $id): void
{
    $this->guard();                          // auth
    $project = Project::getById((int) $id); // lecture
    if (!$project) { $this->redirect('/admin/projets'); } // garde
    $this->render('admin/project_form', [...]);            // rendu
}
```

### DRY — ne pas dupliquer
La fonction `tagColor()` est définie dans `home/index.php` et dupliquée dans
`projects/index.php` et `projects/show.php` avec un guard `function_exists()`.
Dès qu'une 4e vue en a besoin → extraire dans `app/Helpers/TagHelper.php` :
```php
namespace App\Helpers;
function tagColor(string $tag): string { ... }
```
Et inclure via `require_once ROOT_PATH . '/app/Helpers/TagHelper.php';` dans la vue.

### YAGNI — ne pas coder l'avenir
Ne pas ajouter de paramètres optionnels, d'abstractions ou d'interfaces sans
besoin immédiat. Le `Core\Controller` n'a pas besoin d'une interface tant qu'il n'y
a pas de second type de controller.

### Early return — pas d'imbrication
```php
// ✗ Imbriqué
public function show(string $slug): void
{
    $project = Project::getBySlug($slug);
    if ($project) {
        $this->render('projects/show', [...]);
    } else {
        http_response_code(404);
        $this->render('home/404', ['title' => '404']);
    }
}

// ✓ Early return
public function show(string $slug): void
{
    $project = Project::getBySlug($slug);
    if (!$project) {
        http_response_code(404);
        $this->render('home/404', ['title' => '404']);
        return;
    }
    $this->render('projects/show', [...]);
}
```

### Taille des méthodes — max 20 lignes
Si une méthode dépasse 20 lignes de code (hors commentaires/blancs), extraire
une méthode privée. `AdminController::sanitizeProjectPost()` est un bon exemple
d'extraction déjà réalisée.

---

## 2. Sécurité PHP

### Output — toujours `htmlspecialchars()`
```php
// ✗ Jamais
echo $project['title_fr'];
echo $_GET['search'];

// ✓ Toujours (dans les vues)
echo htmlspecialchars($project['title_fr'], ENT_QUOTES, 'UTF-8');
// Ou via $t() qui appelle htmlspecialchars() en interne (Core\Controller)
```

**Exception** : les valeurs déjà passées par `htmlspecialchars()` dans le controller
avant `extract($data)`. Ne pas double-encoder.

### Requêtes BDD — PDO préparé uniquement
```php
// ✗ Jamais (injection SQL)
$pdo->query("SELECT * FROM projects WHERE slug = '{$slug}'");

// ✓ Toujours
$stmt = $pdo->prepare('SELECT * FROM projects WHERE slug = ?');
$stmt->execute([$slug]);

// ✓ Named params (pour les INSERT/UPDATE avec beaucoup de colonnes)
$stmt = $pdo->prepare('INSERT INTO projects (title_fr, slug) VALUES (:title_fr, :slug)');
$stmt->execute(['title_fr' => $titleFr, 'slug' => $slug]);
```

`Core\Database` a déjà `ATTR_EMULATE_PREPARES => false` — les vrais prepared
statements sont actifs ✓.

### CSRF — sur tout formulaire POST
Tous les formulaires POST du projet doivent inclure le token :
```html
<input type="hidden" name="csrf_token"
       value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
```
Et vérifier côté controller avec `hash_equals()` (timing-safe) :
```php
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    $this->redirect('/');
}
```
Formulaires concernés : `/contact` ✓, `/admin/login` ✓, create/update/delete projet ✓.

### Session — regénérer l'ID lors des changements de privilège
```php
session_regenerate_id(true); // true = supprime l'ancienne session
$_SESSION['admin'] = true;   // puis setter les données
```
Déjà en place dans `AdminController::loginPost()` et `logout()` ✓.
À ajouter si un système de rôles utilisateur est ajouté.

### Production — ne pas exposer les erreurs
`Core\Database` expose le message PDO brut dans son exception :
```php
// ✗ Actuel — fuite d'info en prod
throw new \RuntimeException('Connexion BDD échouée : ' . $e->getMessage());

// ✓ Cible — masquer en production
$message = ($_ENV['APP_ENV'] ?? 'prod') === 'local'
    ? 'Connexion BDD échouée : ' . $e->getMessage()
    : 'Erreur de connexion. Contactez l\'administrateur.';
throw new \RuntimeException($message);
```
Même logique pour tout `catch` qui affiche des détails techniques.

### Interdictions absolues
- `eval()` — jamais
- `exec()`, `shell_exec()`, `system()` — jamais sans désinfection stricte
- `extract($_POST)` — jamais (pollue le scope avec des variables arbitraires)
- `$_GET['x']` direct dans une requête ou un rendu — toujours `trim()` + validation

---

## 3. Conventions PHP — PSR-12 + PHP 8.1

### `declare(strict_types=1)` — **manquant dans tous les fichiers app/ et core/**
C'est la lacune principale du projet. À ajouter en tête de chaque fichier PHP :
```php
<?php

declare(strict_types=1);

namespace App\Controllers;
```

Fichiers concernés (aucun ne l'a actuellement) :
- `app/Controllers/*.php`
- `app/Models/Project.php`
- `core/Controller.php`
- `core/Router.php`
- `core/Database.php`

### Type hints — params + retour sur toutes les méthodes
```php
// ✗ Sans types
public function sanitizeProjectPost()
{
    return ['title_fr' => $_POST['title_fr']];
}

// ✓ Avec types complets
private function sanitizeProjectPost(): array
{
    return ['title_fr' => trim($_POST['title_fr'] ?? '')];
}
```
Les méthodes existantes dans `Project.php` et `Core\*` ont déjà les types ✓.
Les closures dans `Controller::render()` aussi ✓.

### Docblocks — sur les méthodes publiques
Seule `Project::parseTags()` a un docblock actuellement. Modèle à suivre :
```php
/**
 * Retourne un projet par son slug, ou false si introuvable.
 */
public static function getBySlug(string $slug): array|false
```
Format minimal : une ligne de description. Pas de `@param`/`@return` redondants
quand les types PHP sont présents — ils dupliquent l'information.

### Namespaces — structure fixe du projet
| Dossier                | Namespace          |
|------------------------|--------------------|
| `app/Controllers/`     | `App\Controllers`  |
| `app/Models/`          | `App\Models`       |
| `app/Services/`        | `App\Services`     |
| `app/Helpers/`         | `App\Helpers`      |
| `core/`                | `Core`             |

### Formatage PSR-12
- Accolades ouvrantes sur la ligne suivante pour classes et méthodes
- 4 espaces d'indentation (pas de tabs)
- Une instruction par ligne
- Pas d'espace entre le nom de la fonction et la parenthèse : `function foo(): void`
- Opérateurs null-safe `?->` et null-coalescing `??` préférés aux isset() imbriqués

---

## 4. BEM SCSS

### Nomenclature stricte
```
.block {}                    // Composant racine
.block__element {}           // Enfant du bloc
.block__element--modifier {} // Variante d'un élément
.block--modifier {}          // Variante du bloc entier
```

### Profondeur maximale — 3 niveaux de nesting SCSS
```scss
// ✗ Trop profond (4 niveaux)
.project-card {
  &__body {
    &__tags {
      .tag { ... }     // 4e niveau — refactoriser
    }
  }
}

// ✓ Maximum 3 niveaux
.project-card {
  &__body { ... }
  &__tags { ... }    // sélecteur plat, pas imbriqué sous __body
}
.project-card__tags .tag { ... }  // ou règle séparée
```

### Pas de sélecteurs ID
```scss
// ✗ Jamais
#nav { ... }
#themeToggle { ... }

// ✓ Toujours des classes
.nav { ... }
.theme-toggle { ... }
```

### Variables — uniquement dans `scss/abstracts/_variables.scss`
Jamais de valeur magique dans un partial :
```scss
// ✗ Dans _nav.scss
.nav { height: 60px; }

// ✓ Via variable
.nav { height: var(--nav-h); } // utiliser les CSS custom props
```
Les nouvelles couleurs et mesures vont dans `_variables.scss` en CSS custom property,
pas en variable SCSS `$`.

### Un composant = un fichier partial
| Composant       | Fichier partial                          |
|-----------------|------------------------------------------|
| `.nav`          | `scss/layout/_nav.scss`                  |
| `.project-card` | `scss/components/_project-card.scss`     |
| `.service-card` | `scss/components/_service-card.scss`     |
| `.btn`          | `scss/components/_buttons.scss`          |
| `.tag`          | `scss/components/_tags.scss`             |
| Nouveaux        | `scss/components/_nom-composant.scss`    |

### Mobile-first avec mixins
```scss
// Dans scss/abstracts/_mixins.scss
@mixin tablet { @media (max-width: 900px) { @content; } }
@mixin mobile { @media (max-width: 560px) { @content; } }

// Usage dans un partial
.contact-form {
  display: grid;
  grid-template-columns: 1fr 1fr;

  @include tablet { grid-template-columns: 1fr; }
}
```
Ne jamais écrire les media queries directement dans les partials — passer par les mixins.

---

## 5. Conventions JavaScript

### Déclarations — `const` par défaut, `let` si réassigné
```js
// ✗
var html = document.documentElement;
let theme = localStorage.getItem('theme'); // theme n'est pas réassigné → const

// ✓
const html  = document.documentElement;
const theme = localStorage.getItem('theme') || 'light';
```
`main.js` applique déjà cette règle ✓. La maintenir sur tout nouveau code.

### Fonctions fléchées pour les callbacks
```js
// ✗
window.addEventListener('scroll', function() { ... });

// ✓
window.addEventListener('scroll', () => { ... }, { passive: true });
```

### Pas de JS inline dans le HTML
```php
// ✗ Dans une vue PHP
<button onclick="confirm('Supprimer ?')">Supprimer</button>

// ✓ Via data-attribute + listener dans main.js
<button data-confirm="Supprimer ce projet ?">Supprimer</button>
```
```js
// Dans main.js
document.querySelectorAll('[data-confirm]').forEach(btn => {
  btn.addEventListener('click', (e) => {
    if (!confirm(btn.dataset.confirm)) e.preventDefault();
  });
});
```

### Délégation d'événements pour les éléments dynamiques
```js
// ✗ Listener sur chaque élément (fragile si DOM change)
document.querySelectorAll('.project-card').forEach(card => {
  card.addEventListener('click', handleClick);
});

// ✓ Délégation sur le parent stable
document.querySelector('.projects__grid')
  ?.addEventListener('click', (e) => {
    const card = e.target.closest('.project-card');
    if (card) handleClick(card);
  });
```

### Gestion d'erreur sur les appels réseau
```js
// ✓ Tout fetch doit avoir un catch
async function fetchProjects() {
  try {
    const res  = await fetch('/api/projects');
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return await res.json();
  } catch (err) {
    console.error('Erreur chargement projets :', err);
    return [];
  }
}
```

### Noms de fonctions — verbe + nom
```js
// ✗
function theme() { ... }
function handler() { ... }

// ✓
function toggleTheme() { ... }
function handleNavScroll() { ... }
function closeNavMenu() { ... }
```

---

## 6. HTML Sémantique

### Heading unique par page — table de référence
| Page                  | `<h1>`                  | `<h2>`                          |
|-----------------------|-------------------------|---------------------------------|
| `/`                   | `.hero__title` (à promouvoir de `<p>` à `<h1>`) | Services, Projets, Profil |
| `/projets`            | "Tous mes projets"      | Titre de chaque `.project-card` |
| `/projets/{slug}`     | Titre du projet         | —                               |
| `/contact`            | "Contact"               | —                               |
| `/admin/projets`      | "Projets"               | —                               |

### Distinction `<button>` vs `<a>`
```html
<!-- <a> = navigation vers une URL -->
<a href="/projets">Voir mes projets</a>
<a href="https://github.com/..." target="_blank">GitHub</a>

<!-- <button> = action sans navigation (JS, submit, toggle) -->
<button id="themeToggle">Changer le thème</button>
<button type="submit">Envoyer</button>
<button type="button" data-confirm="Supprimer ?">Supprimer</button>
```
Jamais `<a href="#">` pour déclencher une action JS — utiliser `<button type="button">`.
Jamais `<div onclick="...">` — utiliser `<button>` ou `<a>` selon le cas.

### Landmark roles — structure imposée par `layouts/main.php`
```html
<body>
  <a href="#main" class="skip-link">...</a>   <!-- skip nav -->
  <header class="nav">                         <!-- landmark header -->
    <nav aria-label="Navigation principale">   <!-- landmark nav -->
  </header>
  <main id="main">                             <!-- landmark main — id pour skip link -->
    <?= $content ?>
  </main>
  <footer class="footer">                      <!-- landmark footer -->
</body>
```

### Labels sur tous les champs
```html
<!-- ✗ Pas de label associé -->
<input type="text" name="name" placeholder="Votre nom">

<!-- ✓ Label lié par for/id -->
<label for="name"><?= $t('contact.name') ?></label>
<input type="text" id="name" name="name" required aria-required="true">
```
Le placeholder n'est pas un substitut au label — toujours les deux.

### `<section>` vs `<div>` vs `<article>`
- `<section>` : bloc thématique avec un titre (services, projets, à propos)
- `<article>` : contenu autonome republitable (chaque `.project-card`, chaque `.service-card`)
- `<div>` : conteneur pur sans sémantique (layout, wrappers)

---

## 7. Conventions Git

### Format des commits
```
type(scope): message court en français (< 72 caractères)

[corps optionnel si le why n'est pas évident]
```

### Types autorisés
| Type       | Usage                                                  |
|------------|--------------------------------------------------------|
| `feat`     | Nouvelle fonctionnalité visible utilisateur            |
| `fix`      | Correction de bug                                      |
| `style`    | CSS/SCSS uniquement, pas de logique                    |
| `refactor` | Restructuration sans changement de comportement        |
| `perf`     | Optimisation performance (lazy loading, cache, etc.)   |
| `docs`     | CLAUDE.md, README, skills, commentaires                |
| `chore`    | Config, dépendances, build (package.json, .env)        |
| `a11y`     | Accessibilité exclusivement                            |
| `seo`      | Balises meta, JSON-LD, sitemap                         |

### Scopes recommandés pour ce projet
`admin`, `contact`, `projects`, `home`, `nav`, `auth`, `db`, `css`, `js`, `lang`

### Exemples corrects
```
feat(projects): ajouter la vue détail projet avec liens GitHub et démo
fix(contact): corriger la validation CSRF sur le formulaire d'envoi
style(nav): ajuster le gap mobile du burger à 44px minimum
refactor(admin): extraire verifyCsrf() depuis les méthodes POST
perf(images): ajouter fetchpriority="high" sur le portrait hero
a11y(nav): ajouter aria-current sur le bouton de langue actif
seo(home): injecter JSON-LD Person + WebSite dans le layout
chore(deps): mettre à jour sass vers la dernière version
```

### Règles de branche
- `main` = code déployable en production, jamais de push direct
- `feat/nom-feature` pour les nouvelles fonctionnalités
- `fix/nom-bug` pour les corrections
- Merge via PR avec au minimum une relecture de diff

---

## Checklist qualité avant tout commit
- [ ] `declare(strict_types=1)` présent en tête du fichier PHP modifié
- [ ] Type hints complets sur toute nouvelle méthode (params + retour)
- [ ] Docblock sur toute nouvelle méthode publique
- [ ] Aucun `echo` sans `htmlspecialchars()` dans les vues
- [ ] Tout formulaire POST a un champ `csrf_token` et une vérification `hash_equals()`
- [ ] Aucune valeur `$_POST`/`$_GET` utilisée directement sans `trim()` + validation
- [ ] Pas de `var` ou `let` inutile en JS
- [ ] Pas de JS inline dans le HTML
- [ ] Pas de sélecteur ID en SCSS
- [ ] Toute nouvelle classe CSS suit la nomenclature BEM
- [ ] Message de commit au format `type(scope): description`
