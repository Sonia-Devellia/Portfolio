# Skill — SEO & Marketing (positionnement et contenu)

## Contexte projet
Site portfolio freelance bilingue FR/EN. Objectif business : générer des
demandes de devis (formulaire `/contact`) auprès de prospects cherchant un·e
développeur·euse full-stack PHP / Python / IA en remote.

Sources de vérité associées :
- `seo.md` — implémentation technique (meta, JSON-LD, sitemap, hreflang).
- `lighthouse.md` — performance, Core Web Vitals.
- `accessibility.md` — a11y (impacte le SEO).

> **Périmètre de cette skill** — positionnement, mots-clés, copywriting,
> structure de contenu, conversion. La technique SEO est dans `seo.md`.

---

## 1. Positionnement — le pitch en une phrase

> *Développeuse full-stack freelance — PHP, Python, JavaScript, intégration
> d'IA. Remote, indépendante, livraisons soignées.*

### Persona cible
| Critère        | Valeur                                            |
|----------------|---------------------------------------------------|
| Rôle           | CTO de startup, lead tech PME, fondateur·rice solo |
| Pain principal | Besoin de livrer un produit web ou un MVP avec IA, pas le temps ou l'équipe en interne |
| Budget         | TJM 400–700 € selon mission                        |
| Géographie     | France + Europe francophone, anglophones bienvenus |
| Canaux         | Recherche directe + Malt + LinkedIn + bouche-à-oreille |

### Différenciation à mettre en avant
- Polyvalence stack (PHP **et** Python, pas un dev de langage unique)
- IA appliquée concrètement (Claude API, scraping intelligent, NLP)
- Solo : pas d'agence, interlocuteur unique
- Code propre : MVC, BEM, accessibilité, sécurité — visible sur le portfolio lui-même

---

## 2. Mots-clés — stratégie

### Tête de longue traîne (volume moyen, compétitif)
- `développeuse freelance php`
- `développeur full stack freelance`
- `freelance python ia`
- `intégration claude api`

### Longue traîne (volume faible, conversion forte)
- `développeuse freelance php remote`
- `freelance dev mvp ia paris`
- `développeur freelance scraping python claude`
- `freelance integration api ia france`
- `développeuse full stack mvc php sécurisé`

### Mots-clés EN (cible secondaire)
- `freelance full-stack developer php python`
- `claude api integration freelance`
- `freelance mvp developer remote`

### Mots-clés à **ne pas** poursuivre
- Génériques sans intention business (`apprendre PHP`, `tutoriel python`).
- Concurrence trop forte sans différenciation (`agence web Paris`).
- Termes obsolètes dans la stack (`développeur jQuery`).

### Densité — règle simple
- Mot-clé principal : 3–5 occurrences naturelles sur la home.
- Variantes lexicales (synonymes, conjugaisons) plutôt que répétition exacte.
- Jamais de bourrage type *"développeur freelance PHP développeuse PHP freelance Paris freelance PHP"*.

---

## 3. Cartographie de contenu — par page

### `/` — Home
**Intention** : capter le visiteur direct ou Google, le convaincre en < 10s, l'amener au CTA.

| Bloc           | Mot-clé visé                              | Action attendue |
|----------------|-------------------------------------------|-----------------|
| Hero           | `développeuse freelance php python ia`    | Lecture / scroll |
| Services       | `développement web sur mesure`, `intégration ia`, `mvp` | Compréhension de l'offre |
| Projets récents| `portfolio développeur php`               | Crédibilité |
| Profil court   | `freelance remote france`                 | Confiance |
| CTA contact    | —                                         | **Conversion** |

H1 cible (à promouvoir depuis le `<p>` actuel) :
> *Sonia Habibi — Développeuse full-stack freelance · PHP · Python · IA*

Meta description (155 car. max) :
> *Développeuse freelance full-stack — PHP, Python, intégrations IA. Sites,
> applications et MVP livrés en remote, code propre et sécurisé.*

### `/projets` — Liste
**Intention** : démontrer la diversité technique, qualifier le visiteur sérieux.
H1 : *Tous mes projets*. Description : court paragraphe orienté « ce que vous
verrez ici » (stacks, types de missions).

### `/projets/{slug}` — Détail
**Intention** : étude de cas crédible, preuve sociale.
Structure type :
1. H1 : nom du projet (titre court, factuel)
2. Eyebrow : tags techno
3. Bloc « contexte » — 2 phrases sur le client / le besoin
4. Bloc « ce qui a été livré » — bullet points concrets
5. Bloc « stack » — liste explicite (lié à `tagColor()` et au schéma)
6. CTA secondaire : *Discuter d'un projet similaire ?*

### `/contact` — Conversion
**Intention** : convertir. Pas de blabla.
H1 : *Contact*. Sous-titre :
> *Décrivez votre projet en quelques lignes — je reviens vers vous sous 48h.*

Formulaire court : nom, email, message. Pas de champs optionnels qui
freinent (téléphone, entreprise → optionnels uniquement).

---

## 4. Copywriting — règles

### Voix éditoriale
- **Direct, sans superlatif vide** : pas de *"développeuse passionnée et créative"*.
- **Précis** : *"j'ai construit un MVP en 4 semaines avec Claude API et MySQL"* > *"j'ai de l'expérience en IA"*.
- **Tu / Vous** : choisir une fois pour toutes (recommandation : **vous**, plus pro pour les prospects en B2B).
- **Verbes d'action** : *concevoir, livrer, intégrer, sécuriser* > *participer à, contribuer à*.

### Anti-clichés
| ✗ À bannir                          | ✓ Préférer                                     |
|-------------------------------------|------------------------------------------------|
| *Passionnée par le code*            | *5 ans à livrer du PHP en prod*                |
| *Solutions innovantes*              | *Intégrations Claude API et scraping Python*   |
| *À l'écoute de vos besoins*         | *Brief en 30 min, livrable en 4 semaines*      |
| *Expert en…* (sans preuve)          | *Voir le projet X dans le portfolio*           |

### Microcopy CTAs
| Contexte         | FR                          | EN                       |
|------------------|-----------------------------|--------------------------|
| CTA principal    | *Discutons de votre projet* | *Let's talk about your project* |
| CTA secondaire   | *Voir mes projets*          | *See my projects*        |
| Form submit      | *Envoyer*                   | *Send*                   |
| Form success     | *Reçu — je reviens vers vous sous 48h.* | *Got it — I'll get back within 48h.* |

### Bilingue — règles
- Même structure, **pas** de traduction littérale. Adapter aux idiomes (un anglophone n'écrit pas comme un francophone).
- Stocker dans `lang/fr.php` et `lang/en.php` avec mêmes clés.
- Hreflang configuré (voir `seo.md`).

---

## 5. Schema.org — au-delà de Person

`seo.md` § 2 couvre `Person` + `WebSite`. Pour le marketing, ajouter aussi :

### `ProfessionalService` — apparaître dans le knowledge panel
À injecter dans `layouts/main.php` une fois, dans le même `@graph` :
```php
{
  "@type": "ProfessionalService",
  "@id": "<?= $_ENV['APP_URL'] ?>#service",
  "name": "Sonia Habibi — Développement web freelance",
  "description": "<?= $schemaDesc ?>",
  "provider": { "@id": "<?= $_ENV['APP_URL'] ?>#sonia" },
  "areaServed": ["FR", "EU", "Worldwide remote"],
  "serviceType": [
    "Développement web full-stack",
    "Intégration API IA (Claude, OpenAI)",
    "MVP & prototypes",
    "Audit et sécurisation PHP"
  ],
  "url": "<?= $_ENV['APP_URL'] ?>",
  "image": "<?= $_ENV['APP_URL'] ?>/assets/images/sonia.webp"
}
```

### `BreadcrumbList` sur `/projets/{slug}`
Aide Google à afficher le fil d'Ariane dans les SERP :
```php
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Accueil", "item": "<?= $_ENV['APP_URL'] ?>/" },
    { "@type": "ListItem", "position": 2, "name": "Projets",  "item": "<?= $_ENV['APP_URL'] ?>/projets" },
    { "@type": "ListItem", "position": 3, "name": "<?= htmlspecialchars($project['title_' . $lang]) ?>" }
  ]
}
</script>
```

---

## 6. Conversion — friction et tracking

### Friction à supprimer
- Pas de cookie consent intrusif si pas d'analytics tiers (privilégier server-side ou Plausible self-hosted).
- Pas de pop-up newsletter — un portfolio freelance n'est pas un blog.
- Pas de captcha agressif sur `/contact` — un honeypot caché suffit en V1.
- Temps de réponse promis dans le sous-titre du formulaire (« sous 48h ») → réduit l'angoisse de l'envoi dans le vide.

### Honeypot anti-spam (sans friction utilisateur)
```html
<!-- Champ caché, un bot le remplira, un humain non -->
<label class="hp" aria-hidden="true">
  Ne pas remplir : <input type="text" name="website" tabindex="-1" autocomplete="off">
</label>
```
```css
.hp { position: absolute; left: -9999px; }
```
```php
// ContactController::send()
if (!empty($_POST['website'])) {
    // Bot — silencieusement OK pour ne pas le faire savoir
    $this->redirect('/contact?ok=1');
    return;
}
```

### Tracking — minimum éthique
- **Plausible** ou **Umami** auto-hébergé > Google Analytics (RGPD-friendly, pas de bandeau).
- Si Google Analytics imposé : bandeau consentement obligatoire + `gtag('consent', 'default', {...denied})`.
- Événement à tracker : `contact_form_submit` (clé du tunnel de conversion).

### Liens sortants
- LinkedIn, GitHub, Malt → `rel="noopener"` (sécurité) + `target="_blank"`.
- Pas de `nofollow` sur ces profils — on **veut** que Google lie l'identité.

---

## 7. Performance perçue (impact SEO)

Recoupe `lighthouse.md` mais côté marketing :
- **LCP < 2.5s** = moins de bounce sur la home.
- **CLS < 0.1** = pas de "ghost click" sur le CTA contact (frustration).
- **INP < 200ms** = sentiment de site "vivant".
- Mauvaise perf = pénalité directe par Google sur les SERP mobile.

---

## 8. Off-site — autorité externe

À faire dans les 30 jours après mise en ligne :
- [ ] Lier le portfolio depuis profils LinkedIn, Malt, GitHub
- [ ] Soumettre `sitemap.xml` à Google Search Console
- [ ] Soumettre à Bing Webmaster Tools (10% des recherches en France)
- [ ] Créer une fiche Google Business Profile (si présence physique légale)
- [ ] Demander un backlink à 1–2 anciens clients ou collaborateurs (témoignage)
- [ ] Publier 1 article tech sur LinkedIn liant le portfolio

---

## 9. Indicateurs de réussite — à suivre

| Métrique                               | Outil               | Cible 3 mois |
|----------------------------------------|---------------------|--------------|
| Sessions organiques / mois             | Plausible / Console | > 200        |
| Position moyenne mots-clés cibles      | Search Console      | < 30         |
| Taux de conversion formulaire contact  | Plausible           | > 2%         |
| Backlinks référents                    | Search Console      | > 5          |
| Core Web Vitals — % URLs "Bonnes"      | Search Console      | 100%         |

---

## 10. Checklist marketing — avant la mise en ligne

### Contenu
- [ ] H1 unique sur chaque page, contenant un mot-clé cible
- [ ] Meta description rédigée pour chaque page (pas auto-générée)
- [ ] Au moins 2 projets avec étude de cas complète (contexte + livrable + stack)
- [ ] Page `/contact` : promesse de délai de réponse explicite
- [ ] Pas de Lorem Ipsum, pas de placeholder

### Crédibilité
- [ ] Photo professionnelle (`sonia.webp`) — visage net, fond neutre
- [ ] Lien LinkedIn + GitHub + Malt visibles dans le footer
- [ ] Au moins une mention de durée d'expérience chiffrée
- [ ] Stack technique listée explicitement (pour matcher les recruteurs / clients)

### Schema.org
- [ ] `Person` injecté (voir `seo.md` § 2)
- [ ] `WebSite` injecté
- [ ] `ProfessionalService` injecté
- [ ] `BreadcrumbList` sur les pages projet
- [ ] Validation [Rich Results Test](https://search.google.com/test/rich-results) passe sans erreur

### Tracking & conversion
- [ ] Outil analytics installé (Plausible / Umami / GA4 + bandeau)
- [ ] Honeypot sur formulaire contact
- [ ] Email de notification configuré et testé end-to-end
- [ ] Page de remerciement après envoi (`/contact?ok=1`) avec vrai message

### Indexation
- [ ] `sitemap.xml` accessible en prod
- [ ] `robots.txt` autorise tout sauf `/admin/`
- [ ] Soumission Search Console + Bing
- [ ] Vérification : `site:sonia-habibi.dev` dans Google sous 7 jours
