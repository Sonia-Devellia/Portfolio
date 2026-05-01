# Skill — Design & Système CSS

## Contexte projet
Site portfolio bilingue, esthétique éditoriale calme : fond clair, contraste
typographique sans/serif, petits accents colorés sur les tags. Light + dark via
`[data-theme="dark"]` posé sur `<html>`, persistance dans `localStorage`.

Sources de vérité :
- Variables : `scss/abstracts/_variables.scss`
- Mixins responsive : `scss/abstracts/_mixins.scss`
- CSS compilé : `public/assets/css/main.css`

> **Périmètre** — système de design (tokens, typo, composants visuels,
> mobile-first). Les règles SCSS pures (BEM, nesting, partials) sont dans
> `code-quality.md` § 4 — ne pas dupliquer.

---

## 0. Complément externe — Impeccable

Cette skill définit le **système** du portfolio (tokens, composants, règles).
Pour un cran supérieur côté **goût visuel** et détection automatique des
anti-patterns "AI générique" (Inter font, purple gradients, cards-dans-cards,
gray text sur fond coloré, bounce easing, etc.), installer le skill externe
[`pbakaus/impeccable`](https://github.com/pbakaus/impeccable).

### Installation
```bash
cd /Applications/MAMP/htdocs/portfolio
npx skills add pbakaus/impeccable
```
Ça pose les fichiers dans `.claude/skills/impeccable/` et auto-détecte le
harnais (Claude Code, Cursor, Gemini CLI, Codex CLI).

### Premier usage
Dans Claude Code :
```
/impeccable teach
```
L'agent pose des questions sur le portfolio (audience, personnalité de marque,
anti-références) et écrit un fichier `.impeccable.md` à la racine du projet.
Tous les autres skills d'Impeccable lisent ce fichier comme contexte.

### Commandes utiles
| Commande              | Usage                                           |
|-----------------------|-------------------------------------------------|
| `/impeccable audit`   | Détecte les anti-patterns IA dans le CSS actuel |
| `/impeccable polish`  | Raffine le design page par page                 |
| `/impeccable typeset` | Corrige la typographie (kerning, line-height, échelle) |
| `/impeccable critique`| Analyse UX complète d'une page                  |

### Articulation avec cette skill
- `design.md` (ici) = **système figé du portfolio** : ce qui est *autorisé* (tokens, classes BEM, breakpoints).
- `impeccable` = **goût et garde-fous** : détecte ce qui ressemble à du design IA générique avant qu'il atterrisse dans le code.

Les deux sont complémentaires. Lancer `/impeccable audit` après chaque grosse
session CSS, et corriger en respectant le système défini ici.

> **Note** — vérifier le résultat de `npx skills add` (chemins, override
> éventuel) avant le premier `/impeccable teach`. Les fichiers d'Impeccable
> arrivent dans `.claude/skills/impeccable/` et ne doivent **pas** écraser
> les skills du projet.

---

## 1. Tokens — variables CSS

**Règle d'or** : aucune valeur hex, rgb ou pixel "magique" dans un partial.
Tout passe par une CSS custom property déclarée dans `_variables.scss`.

### Palette light (cible : éditorial calme, pas de blanc pur agressif)
| Token         | Valeur     | Usage                                |
|---------------|------------|--------------------------------------|
| `--bg`        | `#ffffff`  | Fond principal                       |
| `--bg-soft`   | `#f7f6f4`  | Sections alternées, cards            |
| `--bg-subtle` | `#f0eeeb`  | États hover discrets                 |
| `--border`    | `rgba(0,0,0,.08)` | Séparateurs fins              |
| `--border-md` | `rgba(0,0,0,.14)` | Bords actifs (boutons outline) |
| `--text`      | `#111110`  | Texte primaire                       |
| `--text-2`    | `#5a5956`  | Texte secondaire                     |
| `--text-3`    | `#9a9895`  | Captions, eyebrow                    |
| `--avail`     | `#0f6e56`  | Badge disponibilité (texte)          |
| `--avail-bg`  | `#e1f5ee`  | Badge disponibilité (fond)           |
| `--avail-dot` | `#1d9e75`  | Pastille verte                       |

### Palette dark (`[data-theme="dark"]`)
Inversée tonalement. **Jamais** redéfinie dans un partial — uniquement dans
`_variables.scss`. Tester chaque nouveau composant dans les deux thèmes.

### Tags — 6 couleurs disponibles
`--tag-blue-*`, `--tag-green-*`, `--tag-amber-*`, `--tag-purple-*`,
`--tag-coral-*`, `--tag-gray-*` — chacune en `-bg` + `-txt`. Mappées par
`tagColor()` (helper PHP) selon le tag (`PHP` → blue, `Python` → green, etc.).

### Layout
| Token         | Valeur    | Usage                       |
|---------------|-----------|-----------------------------|
| `--radius-sm` | `6px`     | Inputs                      |
| `--radius-md` | `10px`    | Cards, sections             |
| `--radius-lg` | `14px`    | Cards proéminentes          |
| `--max-w`     | `1160px`  | Largeur max contenu         |
| `--nav-h`     | `60px`    | Hauteur nav (fixe)          |

### Typographie
| Token          | Valeur                                 |
|----------------|----------------------------------------|
| `--font-sans`  | `'DM Sans', system-ui, sans-serif`     |
| `--font-serif` | `'DM Serif Display', Georgia, serif`   |

---

## 2. Typographie

### DM Sans — corps + UI
Tout le texte courant, les boutons, la nav, les formulaires. Poids utilisés :
`400` (normal), `500` (semi-emphase rare).

### DM Serif Display *italic* — accents éditoriaux
Réservée aux moments forts : nom de marque dans la nav, certains titres hero,
mots-clés dans le copywriting (genre `freelance` italicisé). **Jamais** pour le
corps de texte ou les boutons.

```scss
// ✓ Usage type — accent dans un titre
.hero__title em {
  font-family: var(--font-serif);
  font-style: italic;
  font-weight: 400;
}

// ✗ Jamais
.btn { font-family: var(--font-serif); } // illisible en petit
.project-card__desc { font-family: var(--font-serif); } // trop fatiguant
```

### Échelle typographique (référence des tailles utilisées)
| Usage                 | Taille | Poids |
|-----------------------|--------|-------|
| Hero title            | 56px   | 400   |
| H1 page interne       | 36px   | 500   |
| H2 section            | 26px   | 500   |
| H3 card               | 18px   | 500   |
| Corps                 | 15px   | 400   |
| Texte secondaire      | 13–14px| 400   |
| Eyebrow / caption     | 11px   | 400   |
| Tag                   | 11px   | 400   |

### `.eyebrow` — surtitre standard
Déjà défini dans `_typography.scss`. Pattern dans une section :
```html
<p class="eyebrow"><?= $t('section.services.eyebrow') ?></p>
<h2><?= $t('section.services.title') ?></h2>
```

### Hiérarchie heading — un seul `<h1>` par page
Voir `code-quality.md` § 6 et `seo.md` § 3 pour la table de référence.

---

## 3. Composants — catalogue

### `.btn` — boutons
Variantes : `--dark` (CTA principal), `--outline` (CTA secondaire), `--sm` (compact).
```html
<a href="<?= $base ?>/contact" class="btn btn--dark"><?= $t('cta.contact') ?></a>
<a href="<?= $base ?>/projets" class="btn btn--outline"><?= $t('cta.projects') ?></a>
<button type="submit" class="btn btn--dark btn--sm"><?= $t('form.send') ?></button>
```
Règle : **toujours** un `.btn` + une variante. Jamais `.btn` seul.

### `.tag` — étiquettes tech
Une couleur par variante. Déterminée par `tagColor($tag)` côté PHP.
```html
<?php foreach ($tags as $tag): ?>
  <span class="tag tag--<?= tagColor($tag) ?>"><?= htmlspecialchars($tag) ?></span>
<?php endforeach; ?>
```

### `.card` (générique) / `.project-card`, `.service-card` (spécialisés)
Pattern : fond `var(--bg-soft)`, radius `--radius-md`, border `1px solid var(--border)`.
Hover : translation discrète + ombre légère.

### `.section`
Wrapper de section avec padding vertical standardisé.
```html
<section class="section">
  <div class="section__inner">
    <p class="eyebrow">…</p>
    <h2>…</h2>
    <!-- contenu -->
  </div>
</section>
```

### `.eyebrow`
Surtitre uppercase espacé. Couleur `--text-3`. Une utilisation par section max.

---

## 4. Mobile-first via mixins

**Règle absolue** : mobile par défaut, desktop en surcouche via media queries.
Pas l'inverse.

```scss
// ✓ Mobile-first
.hero {
  padding: 32px 16px;            // ← mobile par défaut
  font-size: 32px;

  @include tablet {              // ← desktop / tablette en surcouche
    padding: 64px 32px;
    font-size: 56px;
  }
}

// ✗ Desktop-first (à proscrire)
.hero {
  padding: 64px 32px;
  font-size: 56px;

  @media (max-width: 900px) {    // ← inverse, fragile
    padding: 32px 16px;
  }
}
```

### Breakpoints — uniquement via mixins
| Mixin       | Breakpoint           | Usage                    |
|-------------|----------------------|--------------------------|
| `tablet`    | `max-width: 900px`   | Tablettes + petits desktops |
| `mobile`    | `max-width: 560px`   | Smartphones              |

Pas de `@media (max-width: 768px)` à la main dans un partial — passer par un
mixin. Si un nouveau seuil est nécessaire, l'ajouter dans `_mixins.scss`.

### Cibles tactiles
Tout élément interactif doit faire **au minimum 44×44 px** sur mobile (Apple
HIG / WCAG 2.5.5). Le burger nav et les chips de langue ont déjà cette taille.

---

## 5. Espacements et rythme

### Échelle de spacing (cohérence visuelle)
Pas de `padding: 17px` sortis de nulle part. Réutiliser des multiples de 4 :
`4 / 8 / 12 / 16 / 20 / 24 / 32 / 48 / 64 / 96`.

### Sections — padding vertical type
| Contexte           | Mobile | Desktop |
|--------------------|--------|---------|
| Section standard   | 48px   | 96px    |
| Hero               | 64px   | 120px   |
| Card padding       | 16px   | 24px    |

### Container max-width
Tout contenu de page passe par un wrapper `.section__inner` (ou équivalent)
limité à `var(--max-w)` avec `margin-inline: auto` et un padding latéral
mobile-first.

---

## 6. Mode sombre

### Toggle
`<button id="themeToggle">` dans la nav. JS dans `main.js` lit/écrit
`localStorage['theme']` et applique `[data-theme="dark"]` sur `<html>`.

### Règles
- **Aucune** couleur hardcodée dans un composant — tout via tokens.
- Tester systématiquement chaque composant en dark : changements de contraste,
  visibilité des borders, lisibilité des tags.
- Les images PNG transparentes peuvent passer en `filter: invert(0.9)` si nécessaire.
- Les ombres : utiliser `box-shadow: 0 1px 2px var(--border)` plutôt que rgba en dur.

### Préférence système
À ajouter dans `main.js` si pas encore fait — initialiser sur `prefers-color-scheme` :
```js
const stored = localStorage.getItem('theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
const theme = stored || (prefersDark ? 'dark' : 'light');
document.documentElement.dataset.theme = theme;
```

---

## 7. États interactifs

### Hover
- Boutons `--dark` : `opacity: 0.82`
- Boutons `--outline` : `background: var(--bg-soft)`
- Cards : `transform: translateY(-2px)` + ombre douce
- Liens nav : sous-ligné progressif

### Focus — accessibilité prioritaire
Voir `accessibility.md`. Règle : **jamais** `outline: none` sans remplacement
visible. Le projet utilise un focus ring custom dans `_focus.scss`.
```scss
:focus-visible {
  outline: 2px solid var(--text);
  outline-offset: 2px;
  border-radius: var(--radius-sm);
}
```

### Disabled
`opacity: 0.5; cursor: not-allowed;` + retirer le hover.

### Active (pressed)
Léger `transform: translateY(1px)` ou `opacity: 0.9` pour le retour tactile.

---

## 8. Iconographie

Pas de librairie d'icônes lourde (pas de Font Awesome, pas de Material Icons).
Préférer :
- SVG inline dans les vues, avec `aria-hidden="true"` si décoratif
- Ou une sprite `assets/icons/sprite.svg` + `<svg><use href="...#nom"/></svg>`
- Stroke / fill via `currentColor` pour hériter de la couleur du parent

```html
<!-- ✓ SVG décoratif inline -->
<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="2" aria-hidden="true">
  <path d="M5 12h14M12 5l7 7-7 7"/>
</svg>
```

---

## 9. Images

Voir `seo.md` § 6 et `lighthouse.md` pour les détails performance. Rappels design :
- Format : WebP en priorité, fallback JPEG via `<picture>` si nécessaire.
- Dimensions : toujours `width` + `height` HTML pour réserver l'espace (CLS).
- Hero : `loading="eager"` + `fetchpriority="high"`.
- Reste : `loading="lazy"` + `decoding="async"`.

---

## 10. Règles de non-régression

Avant tout commit CSS :

- [ ] Aucune couleur hex/rgb hardcodée dans un partial — tokens uniquement.
- [ ] Aucune media query inline — passer par les mixins `tablet`/`mobile`.
- [ ] Le composant fonctionne en light **ET** dark (vérifier visuellement).
- [ ] Toute nouvelle classe suit BEM strict (`.block__element--modifier`).
- [ ] Cibles tactiles ≥ 44px sur mobile.
- [ ] Focus visible sur tout élément interactif.
- [ ] Heading typographique cohérent avec l'échelle § 2.
- [ ] `npm run build` passe sans warning SCSS.
- [ ] Diff `public/assets/css/main.css` revu avant commit (le SCSS recompile).

---

## 11. Anti-patterns observés ou à éviter

```scss
// ✗ Couleur hardcodée
.contact-page__form-wrap { background: #f7f6f4; }
// ✓
.contact-page__form-wrap { background: var(--bg-soft); }

// ✗ Media query manuelle
@media (max-width: 768px) { … }
// ✓
@include tablet { … }

// ✗ Sélecteur ID
#contactForm { … }
// ✓
.contact-form { … }

// ✗ !important
.btn--dark { background: black !important; }
// ✓ — réorganiser la spécificité, jamais !important sauf cas extrême documenté

// ✗ Nesting > 3 niveaux
.section { .row { .col { .item { … } } } }
// ✓ — extraire en classes plates BEM

// ✗ Style inline dans la vue
<div style="margin-top: 24px;">
// ✓ — créer une classe utilitaire ou un modifier
```
