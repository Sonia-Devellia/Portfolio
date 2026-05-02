# Tests e2e — Portfolio Sonia Habibi

Tests end-to-end du formulaire de contact, exécutés via `bash + curl` contre
l'application en local (MAMP) ou en staging.

## Lancer les tests

```bash
# Cible par défaut : http://localhost:8888/portfolio/public
bash tests/e2e/contact-form.sh

# Avec verbose
VERBOSE=1 bash tests/e2e/contact-form.sh

# Contre un autre serveur (staging, prod sur o2switch)
BASE_URL=https://staging.sonia-habibi.dev bash tests/e2e/contact-form.sh
```

Exit code = nombre de tests échoués (0 = tout OK).

## Couverture

| # | Scénario                                              | Catégorie  |
|---|-------------------------------------------------------|------------|
| 1 | GET /contact charge la page + CSRF présent            | Smoke      |
| 2 | POST avec données valides → redirect 302              | Happy path |
| 3 | POST sans CSRF → refus + alerte erreur                | Sécurité   |
| 4 | POST avec mauvais CSRF → refus + alerte erreur        | Sécurité   |
| 5 | POST avec champs vides → refus                        | Validation |
| 6 | POST avec email invalide (sans `@`) → refus           | Validation |
| 7 | POST avec message < 10 caractères → refus             | Validation |
| 8 | POST tentative d'injection CRLF dans email → refus    | Sécurité   |
| 9 | POST tentative d'injection CRLF dans nom → refus      | Sécurité   |

## Limites — ce que le script ne teste **pas**

Ces points doivent être validés **manuellement** ou nécessitent un mailcatcher
(ex: [MailHog](https://github.com/mailhog/MailHog), Mailpit) :

- [ ] Le mail est réellement reçu côté `MAIL_TO` après un POST valide
- [ ] Le sujet du mail s'affiche bien `Portfolio — Message de Test Person`
  avec les accents préservés (encodage UTF-8 RFC 2047)
- [ ] Le `Reply-To` est bien l'email de l'expéditeur
- [ ] Aucun header SMTP injecté n'est passé en cas de tentative CRLF (test 8)

## Vérification manuelle complémentaire

### Visuel

- [ ] Page `/contact` rendue : asymétrie 2/5 ↔ 3/5, filet vertical à gauche, form-wrap à droite
- [ ] Inputs : focus ring sophistiqué (border + box-shadow), height ≥ 48px
- [ ] Submit : couleur foncée, hover opacité 0.88, pas full-width
- [ ] Alert success/error : filet vertical 3px à gauche
- [ ] Mobile (< 900px) : passage en colonne unique, gap 48px

### Logs

Après quelques tentatives échouées, vérifier les logs de sécurité :

```bash
tail -f storage/logs/security.log
```

Tu devrais voir :
- `contact_csrf_rejected` après les tests 3-4
- `contact_header_injection_attempt` après les tests 8-9

### Mail réel

Sur MAMP local, `mail()` n'envoie pas vraiment de mail sans config SMTP.
Pour tester vraiment l'envoi avant prod :

1. **Option mailcatcher** : `brew install mailpit && mailpit`, puis configurer
   PHP pour pointer sur `localhost:1025` via sendmail_path.
2. **Option SMTP staging** : ajouter `MAIL_HOST/MAIL_USER/MAIL_PASS` dans
   `.env` pointant sur o2switch ou un compte test, et tester depuis MAMP.
3. **Option PHPMailer** : remplacer `mail()` par `PHPMailer` pour avoir un
   contrôle SMTP explicite (voir issue #TBD).

## Dépannage

### "Serveur injoignable à $BASE_URL"

- MAMP est-il démarré ?
- Le port est-il 8888 ou 80 ? Adapter `BASE_URL`.
- L'URL contient-elle bien `/portfolio/public` (le DocumentRoot) ?

### Tous les tests passent mais aucun mail

Normal en MAMP local sans SMTP. Voir "Mail réel" ci-dessus.
Le script teste la **chaîne de validation**, pas l'envoi SMTP réel.

### Tests CRLF échouent

Si les tests 8-9 ne passent pas → le `ContactController::hasHeaderInjection()`
n'a pas été appliqué. Vérifier que la version actuelle du Controller contient
bien la méthode et qu'elle est appelée avant `sendMail()`.
