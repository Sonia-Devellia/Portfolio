# Skill — Accessibilité WCAG 2.1 AA

## Contexte projet
Stack : PHP 8.1 MVC, CSS vanilla avec variables custom, JS vanilla.
Les règles ci-dessous s'appliquent à toutes les vues dans `app/Views/` et au layout `layouts/main.php`.

---

## 1. HTML sémantique

### Hiérarchie des titres
- Chaque page a **un seul `<h1>`** — pas dans le layout, dans la vue.
- `layouts/main.php` ne contient aucun heading : c'est la vue qui porte le `<h1>`.
- L'ordre doit être strict : h1 → h2 → h3, sans sauter de niveau.

```php
<!-- projects/index.php : h1 sur le titre de page, h2 sur les cartes -->
<h1 class="section__title"><?= $t('projects.all') ?></h1>
...
<h2 class="project-card__title"><?= $title ?></h2>

<!-- projects/show.php : h1 = titre du projet -->
<h1 class="project-detail__title"><?= $pTitle ?></h1>
```

### Landmarks et éléments structurants
- `<header>` pour `.nav` — déjà en place dans `layouts/main.php`
- `<main id="main">` avec `id` pour le lien "skip to content"
- `<footer>` pour `.footer`
- `<nav aria-label="Navigation principale">` — déjà en place
- `<section>` pour chaque bloc thématique, pas `<div>` si le contenu a un sens autonome
- `<article>` pour chaque `.project-card` (contenu autonome et republitable)

### Lien "skip to content" — MANQUANT à ajouter dans `layouts/main.php`
```html
<!-- Insérer juste après <body>, avant <header> -->
<a href="#main" class="skip-link">Aller au contenu principal</a>
```
```css
/* À ajouter dans main.css ou scss/base/_reset.scss */
.skip-link {
  position: absolute;
  top: -100%;
  left: 0;
  padding: 8px 16px;
  background: var(--text);
  color: var(--bg);
  font-size: 13px;
  z-index: 9999;
  border-radius: 0 0 var(--radius-sm) 0;
}
.skip-link:focus { top: 0; }
```

---

## 2. ARIA labels

### Éléments interactifs sans texte visible
```html
<!-- Nav burger — déjà correct dans layouts/main.php -->
<button class="nav__burger" id="navBurger"
        aria-label="Menu" aria-expanded="false">

<!-- Theme toggle — déjà correct -->
<button class="theme-toggle" id="themeToggle"
        aria-label="Changer le thème">

<!-- Lang switch — ajouter aria-label sur le container, aria-current sur le bouton actif -->
<div class="lang-switch" aria-label="Choisir la langue">
    <a href="/lang/fr"
       class="lang-switch__btn <?= $lang === 'fr' ? 'is-active' : '' ?>"
       <?= $lang === 'fr' ? 'aria-current="true"' : '' ?>>FR</a>
```

### Liens externes — indiquer l'ouverture dans un nouvel onglet
```php
<!-- Pour tous les liens target="_blank" -->
<a href="<?= htmlspecialchars($project['github_url']) ?>"
   target="_blank" rel="noopener"
   aria-label="<?= $title ?> sur GitHub (nouvel onglet)">
    <?= $t('projects.github') ?>
</a>
```

### Formulaire contact — `contact/index.php`
- Chaque `<input>` et `<textarea>` doit avoir un `<label>` lié via `for`/`id` — déjà en place.
- Ajouter `aria-required="true"` sur les champs obligatoires.
- Messages d'erreur/succès : utiliser `role="alert"` pour que les screen readers les annoncent.

```php
<?php if ($success): ?>
    <div class="alert alert--success" role="alert" aria-live="polite">
        <?= $t('contact.success') ?>
    </div>
<?php elseif ($error): ?>
    <div class="alert alert--error" role="alert" aria-live="assertive">
        <?= $t('contact.error') ?>
    </div>
<?php endif; ?>
```

---

## 3. Contrastes de couleurs (valeurs exactes du projet)

Mode **light** — ratios approximatifs sur `--bg: #ffffff` :
| Variable        | Valeur hex | Ratio | AA normal | AA large |
|-----------------|------------|-------|-----------|----------|
| `--text`        | `#111110`  | ~19:1 | ✓         | ✓        |
| `--text-2`      | `#5a5956`  | ~6.6:1| ✓         | ✓        |
| `--text-3`      | `#9a9895`  | ~2.7:1| **FAIL**  | ✓ (≥3:1 pour large 18px+) |
| `--avail`       | `#0f6e56`  | ~5.1:1| ✓         | ✓        |

Mode **dark** — ratios sur `--bg: #111110` :
| Variable   | Valeur hex | Ratio | AA normal |
|------------|------------|-------|-----------|
| `--text`   | `#f0eeeb`  | ~18:1 | ✓         |
| `--text-2` | `#a09e9b`  | ~7.8:1| ✓         |
| `--text-3` | `#6a6866`  | ~3.8:1| **FAIL** pour texte < 18px |

**Action requise pour `--text-3`** : n'utiliser cette couleur que pour du texte décoratif/large
(`.eyebrow` 11px en uppercase → large text, OK). Ne jamais mettre de contenu informatif critique
uniquement en `--text-3`.

### Tags — contrastes validés
Les combinaisons `tag--*` sont toutes validées en light et dark car les paires fond/texte
ont été choisies avec un ratio ≥ 4.5:1.

---

## 4. Navigation clavier

### Focus visible
Le CSS actuel n'a pas de `:focus-visible` explicite. Ajouter dans `scss/base/_reset.scss` :
```css
:focus-visible {
  outline: 2px solid var(--text);
  outline-offset: 3px;
  border-radius: 2px;
}
button:focus-visible,
a:focus-visible {
  outline: 2px solid var(--text);
  outline-offset: 3px;
}
```

### Ordre de tabulation
- Ne jamais utiliser `tabindex` > 0.
- Les cartes `.project-card` doivent avoir leur lien principal en premier dans le DOM.
- Le lien "skip to content" doit être le premier élément focusable de la page.

### Composants interactifs custom
Le burger `#navBurger` gère déjà `aria-expanded` via `main.js`. S'assurer que
`.nav__links--open` rende les liens focusables (pas de `visibility:hidden` sans CSS conditionnel).

---

## 5. Images

### Alt texts — règles par type
```php
<!-- Photo portrait héro — texte descriptif -->
<img src="/assets/images/sonia.webp"
     alt="Sonia Habibi, développeuse full-stack"
     ...>

<!-- Thumbnail projet — titre du projet suffit -->
<img src="<?= htmlspecialchars($project['thumbnail']) ?>"
     alt="<?= $title ?>">

<!-- Image purement décorative — alt vide -->
<img src="/assets/images/decoration.webp" alt="">
```

---

## 6. Préférences système

### Mouvement réduit — `main.js` IntersectionObserver
```js
// Wrapper à ajouter autour de l'observer dans main.js
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion) {
  document.querySelectorAll('.service-card, .project-card, .timeline-item')
    .forEach(el => {
      el.classList.add('fade-up');
      observer.observe(el);
    });
}
```

### Couleurs forcées (Windows High Contrast)
Utiliser `currentColor` pour les SVG icons (déjà fait dans `.theme-toggle`).
Ne pas véhiculer d'information par la couleur seule — les tags ont toujours un label texte.

---

## Checklist avant livraison
- [ ] Un seul `<h1>` par page
- [ ] `<a href="#main">` skip link présent
- [ ] Tous les `<img>` ont un `alt` (vide si décoratif)
- [ ] Labels liés à tous les champs de formulaire
- [ ] Alertes avec `role="alert"`
- [ ] `aria-expanded` sur le burger mobile
- [ ] `aria-current` sur le lien de langue actif
- [ ] Liens externes avec label de destination
- [ ] `:focus-visible` visible sur tous les éléments interactifs
- [ ] Pas de contenu critique uniquement en `--text-3`
- [ ] `prefers-reduced-motion` respecté dans le JS
