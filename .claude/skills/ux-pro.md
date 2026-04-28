# Skill — UX Professionnel

## Contexte projet
Portfolio freelance de Sonia Habibi — cible : recruteurs, startups, clients directs.
**Objectif de conversion unique** : déclencher un contact (formulaire `/contact` ou lien Malt/LinkedIn).
Stack : PHP MVC, CSS vanilla (variables custom dans `_variables.scss`), JS vanilla.

---

## 1. Hiérarchie visuelle

### Niveaux typographiques disponibles
```
--font-serif (DM Serif Display italic) → Logo, accent émotionnel
--font-sans  (DM Sans)                → Tout le reste

Tailles en usage :
  46px   → hero__title         — promesse principale
  22px   → section__title      — titre de section
  20px   → stats-bar__num      — chiffres clés
  15px   → service-card__title — titres de cards
  14px   → project-card__title — titres secondaires
  13px   → corps de texte, boutons
  12px   → liens, petits labels
  11px   → eyebrow, tags, meta
```

### Règle de contraste attentionnel
1er niveau : `--text` (`#111110`) — titres, CTA primaires
2e niveau : `--text-2` (`#5a5956`) — corps, descriptions
3e niveau : `--text-3` (`#9a9895`) — eyebrow, timestamps, labels secondaires

Ne jamais mettre une information de conversion en `--text-3`.
L'eyebrow est une **étiquette catégorielle**, jamais un argument de vente.

---

## 2. Placement des CTA

### Règle de l'offre → preuve → action
Chaque section doit suivre ce séquencement :
```
Eyebrow (contexte)
  → Titre (promesse)
    → Corps/Cards (preuve)
      → CTA (action)
```

### CTA primaire vs secondaire
```html
<!-- Primaire — action principale de conversion -->
<a href="/contact" class="btn btn--dark">Écrire → </a>

<!-- Secondaire — action de découverte -->
<a href="/projets" class="btn btn--outline">Voir mes projets</a>
```

Règle : **jamais deux `btn--dark` côte à côte**. Le CTA sombre capte l'oeil — un seul
par zone de décision. Dans `.hero__actions`, c'est "Voir mes projets" qui est dark car
les projets sont la preuve, pas le contact.

### Positions de CTA dans les layouts existants
| Zone              | CTA primaire          | CTA secondaire      |
|-------------------|-----------------------|---------------------|
| `.hero__actions`  | `/projets` (dark)     | `/contact` (outline)|
| `.section.projects` | — | "Voir tous →" (outline, petit)|
| `.cta-band`       | `/contact` (dark)     | LinkedIn, GitHub, Malt (outline) |
| `.nav`            | `/contact → ` (dark, mobile) | — |

---

## 3. Pattern de lecture F

La grille de contenu respecte le pattern F naturel :
- Ligne 1 (eye entry) : `.hero__content` à gauche, image à droite → accroche immédiate
- Ligne 2 (scan horizontal) : `.stats-bar` en 4 colonnes → scan rapide des crédentiels
- Zone F basse : `.services__grid` en 3 colonnes → détail technique progressif

**Règle** : L'information de conversion (disponibilité, tarifs, contact) doit être visible
dans le premier tiers gauche de chaque zone, pas en dernier.

---

## 4. Espacement et respiration

### Valeurs canoniques du système (ne pas inventer d'autres)
```scss
// Padding de section
.section { padding: 64px 24px; }            // desktop
@media (max-width: 560px) { padding: 48px 16px; }  // mobile

// Gaps de grilles
.services__grid  { gap: 12px; }
.projects__grid  { gap: 12px; }
.hero            { gap: 48px; }

// Espacement interne des cards
.service-card    { padding: 24px; }
.project-card__body { padding: 16px; }

// Espacement typographique
margin-bottom: 8px   → eyebrow → titre
margin-bottom: 16px  → titre → sous-titre/corps
margin-bottom: 28px  → sous-titre → actions
margin-bottom: 32px  → section__head → grid
```

**Règle** : Utiliser uniquement ces valeurs. Pas de padding à 30px ou margin à 20px inventés.

---

## 5. Mobile-first interactions

### Points de rupture du projet
```scss
@media (max-width: 900px)  { /* Tablet — grille passe en 1 colonne */ }
@media (max-width: 560px)  { /* Mobile — padding réduit */ }
```

### Navigation mobile
Le burger est déjà géré via `main.js` (`aria-expanded`, `nav__links--open`).
S'assurer que la zone de tap est ≥ 44×44px (recommandation Apple/Google) :
```css
.nav__burger { padding: 10px; min-width: 44px; min-height: 44px; }
```

### Interactions tactiles
```css
/* Hover uniquement sur écrans pointeurs (pas de flash au tap sur mobile) */
@media (hover: hover) {
  .project-card:hover { border-color: var(--border-md); transform: translateY(-2px); }
  .service-card:hover { border-color: var(--border-md); }
  .btn--dark:hover    { opacity: .82; }
  .btn--outline:hover { background: var(--bg-soft); }
}
```

---

## 6. États de chargement

### Skeleton screens — pour les listes de projets chargées en JS (futur)
Pattern à utiliser si une section se charge en AJAX :
```html
<div class="skeleton project-card" aria-busy="true" aria-label="Chargement...">
  <div class="skeleton__thumb"></div>
  <div class="skeleton__body">
    <div class="skeleton__line skeleton__line--short"></div>
    <div class="skeleton__line"></div>
    <div class="skeleton__line skeleton__line--short"></div>
  </div>
</div>
```
```css
.skeleton__thumb, .skeleton__line {
  background: linear-gradient(90deg, var(--bg-subtle) 25%, var(--bg-soft) 50%, var(--bg-subtle) 75%);
  background-size: 200% 100%;
  animation: shimmer 1.2s infinite;
  border-radius: var(--radius-sm);
}
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
```

### Bouton de formulaire en cours d'envoi
```js
// Dans main.js — sur le submit du formulaire contact
const form = document.querySelector('.contact-form');
form?.addEventListener('submit', () => {
  const btn = form.querySelector('[type="submit"]');
  if (btn) { btn.disabled = true; btn.textContent = 'Envoi…'; }
});
```

---

## 7. États d'erreur

### Formulaire contact — `contact/index.php`
```php
<!-- Afficher les erreurs au niveau du champ, pas seulement en haut -->
<div class="form-group <?= $error ? 'form-group--error' : '' ?>">
    <label for="email"><?= $t('contact.email') ?></label>
    <input type="email" id="email" name="email"
           aria-describedby="<?= $error ? 'email-error' : '' ?>"
           required>
    <?php if ($error): ?>
        <span id="email-error" class="form-group__error" role="alert">
            <?= $t('contact.error') ?>
        </span>
    <?php endif; ?>
</div>
```
```css
.form-group--error input,
.form-group--error textarea {
  border-color: #c0392b;
  box-shadow: 0 0 0 2px rgba(192, 57, 43, 0.15);
}
.form-group__error {
  font-size: 12px;
  color: #c0392b;
  margin-top: 4px;
  display: block;
}
```

### Page 404 — `home/404.php`
La 404 doit proposer une sortie active, pas juste "cette page n'existe pas" :
```php
<section class="section">
    <h1><?= $t('404.title') ?></h1>
    <p><?= $t('404.sub') ?></p>
    <a href="/" class="btn btn--dark"><?= $t('404.back') ?></a>
    <!-- Ajouter un lien vers les projets aussi -->
    <a href="/projets" class="btn btn--outline"><?= $t('nav.projects') ?></a>
</section>
```

---

## 8. États vides

### Admin — liste projets vide (`admin/projects.php`)
```html
<div class="admin-empty">
    <p>Aucun projet pour l'instant.</p>
    <a href="/admin/projets/new" class="btn btn--dark">Créer le premier projet</a>
</div>
```

### Page projets publique vide (si BDD vide)
```php
<?php if (empty($projects)): ?>
<div class="projects__empty">
    <p><?= ($_SESSION['lang'] ?? 'fr') === 'fr' ? 'Projets à venir.' : 'Projects coming soon.' ?></p>
</div>
<?php endif; ?>
```

---

## 9. Micro-interactions

### Transitions en place dans le design system
```css
/* Durées canoniques — ne pas inventer d'autres valeurs */
transition: opacity .15s           /* boutons, liens */
transition: background .15s        /* cards au hover */
transition: border-color .15s      /* cards au hover */
transition: transform .15s         /* project-card:hover translateY(-2px) */
transition: transform .3s          /* project-card img scale au hover */
transition: background .2s, color .2s  /* dark mode switch */
```

### IntersectionObserver — `main.js` (déjà en place)
Les classes `.fade-up` et `.is-visible` sont ajoutées par JS sur `.service-card`,
`.project-card`, `.timeline-item`. Ajouter le CSS d'animation :
```css
.fade-up { opacity: 0; transform: translateY(16px); transition: opacity .4s, transform .4s; }
.fade-up.is-visible { opacity: 1; transform: none; }
```

### Indicateur de disponibilité — `.nav__avail-dot`
```css
/* Déjà en place dans main.css */
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
.nav__avail-dot { animation: pulse 2s infinite; }
```

---

## 10. Design orienté conversion

### Signaux de confiance — vérifier leur présence sur chaque page
1. **Disponibilité visible** : `.nav__avail` avec dot animé ✓ (dans nav)
2. **Crédentiels quantifiés** : `.stats-bar` (5+ projets, stack, IA, remote) ✓
3. **Preuves sociales** : liens GitHub, Malt, LinkedIn dans footer et CTA band ✓
4. **Réassurance délai** : `contact.success` mentionne "sous 24h" ✓

### Règle des 3 clics
Toute action de conversion (envoyer un message, voir un projet, visiter GitHub) doit
être accessible en **≤ 3 clics** depuis n'importe quelle page du portfolio.
- `/contact` : accessible depuis nav, hero, cta-band → 1 clic ✓
- `/projets/{slug}` : depuis home cards → 2 clics ✓
- GitHub d'un projet : depuis home cards → 1 clic ✓

### Formulaire contact — friction minimale
3 champs uniquement (nom, email, message). Pas de téléphone, pas de budget, pas de CAPTCHA visible.
Le CSRF est invisible pour l'utilisateur — ne jamais ajouter de champ honeypot visible.
