#!/usr/bin/env bash
#
# Tests e2e du formulaire de contact
# ------------------------------------
# Exécute des requêtes HTTP réelles contre l'application en local (MAMP).
# Couvre : Happy path, Validation, Sécurité CSRF (timing-safe + anti-CRLF).
#
# Usage :
#   bash tests/e2e/contact-form.sh
#   BASE_URL=http://localhost:8888/portfolio/public bash tests/e2e/contact-form.sh
#   VERBOSE=1 bash tests/e2e/contact-form.sh
#
# Exit code : 0 si tout passe, sinon nombre de tests échoués.

set -u

# ─── Config ──────────────────────────────────────────────────────────────
BASE_URL="${BASE_URL:-http://localhost:8888/portfolio/public}"
VERBOSE="${VERBOSE:-0}"
COOKIE_JAR="$(mktemp -t contact-e2e-cookies.XXXXXX)"
TMP_DIR="$(mktemp -d -t contact-e2e.XXXXXX)"

trap 'rm -rf "$COOKIE_JAR" "$TMP_DIR"' EXIT

PASS=0
FAIL=0
CURRENT_TEST=""

# ─── Couleurs ────────────────────────────────────────────────────────────
if [ -t 1 ]; then
    GREEN='\033[0;32m'; RED='\033[0;31m'; DIM='\033[2m'; BOLD='\033[1m'; RESET='\033[0m'
else
    GREEN=''; RED=''; DIM=''; BOLD=''; RESET=''
fi

# ─── Helpers ─────────────────────────────────────────────────────────────

log()  { [ "$VERBOSE" = "1" ] && echo -e "${DIM}    $*${RESET}" >&2; return 0; }
section() { echo; echo -e "${BOLD}» $*${RESET}"; CURRENT_TEST="$*"; }

assert() {
    local label="$1" cond="$2"
    if [ "$cond" = "true" ]; then
        echo -e "  ${GREEN}✓${RESET} $label"
        PASS=$((PASS + 1))
    else
        echo -e "  ${RED}✗${RESET} $label"
        FAIL=$((FAIL + 1))
    fi
}

# Reset cookie jar — démarre une session fraîche (utile entre tests)
fresh_session() { : > "$COOKIE_JAR"; }

# Récupère le csrf_token courant en visitant /contact
# La vue split l'input sur 2 lignes (name="..." \n value="...") → tr -d '\n' aplatit.
# --compressed gère un éventuel mod_deflate côté Apache.
# Pattern élargi : accepte n'importe quel ordre d'attributs.
fetch_csrf() {
    local html token
    html=$(curl -s --compressed -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")

    # Première tentative : name="csrf_token" puis value="..." (ordre standard)
    token=$(echo "$html" \
        | tr -d '\n' \
        | grep -oE 'name="csrf_token"[^>]*value="[^"]+"' \
        | sed -E 's/.*value="([^"]+)".*/\1/' \
        | head -n 1)

    # Deuxième tentative : value="..." puis name="csrf_token" (ordre inversé)
    if [ -z "$token" ]; then
        token=$(echo "$html" \
            | tr -d '\n' \
            | grep -oE 'value="[a-f0-9]{32,}"[^>]*name="csrf_token"' \
            | sed -E 's/value="([^"]+)".*/\1/' \
            | head -n 1)
    fi

    # Debug : si toujours rien, dump le snippet autour de "csrf" pour diagnostic
    if [ -z "$token" ] && [ "${VERBOSE:-0}" = "1" ]; then
        echo "    [debug] HTML autour de 'csrf' (sans extraction) :" >&2
        echo "$html" | tr -d '\n' | grep -oE '.{0,100}csrf.{0,160}' | head -3 >&2
        echo "    [debug] longueur HTML totale : $(echo "$html" | wc -c) octets" >&2
    fi

    echo "$token"
}

# POST JSON-style avec follow=0, retourne le code HTTP
post_contact() {
    local body_file="$1"
    curl -s -o "$TMP_DIR/post_body.html" \
         -w "%{http_code}" \
         -b "$COOKIE_JAR" -c "$COOKIE_JAR" \
         -X POST "$BASE_URL/contact" \
         --data "@$body_file"
}

# ─── Préflight : MAMP up ? ──────────────────────────────────────────────
echo -e "${BOLD}Tests e2e — formulaire de contact${RESET}"
echo -e "${DIM}Cible : $BASE_URL${RESET}"
echo

if ! curl -fs -o /dev/null --max-time 5 "$BASE_URL/"; then
    echo -e "${RED}✗ Serveur injoignable à $BASE_URL${RESET}"
    echo -e "${DIM}  Vérifie que MAMP est démarré (ports 80/8888 selon config).${RESET}"
    echo -e "${DIM}  Override : BASE_URL=http://... bash tests/e2e/contact-form.sh${RESET}"
    exit 99
fi

# ─── Test 1 : GET /contact — page chargée + CSRF présent ─────────────────
section "Test 1 — GET /contact"
fresh_session
status=$(curl -s -o "$TMP_DIR/contact_get.html" -w "%{http_code}" \
         -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Status 200"                   "$([ "$status" = "200" ] && echo true || echo false)"
assert "Champ name présent"           "$(grep -q 'name="name"' "$TMP_DIR/contact_get.html" && echo true || echo false)"
assert "Champ email présent"          "$(grep -q 'name="email"' "$TMP_DIR/contact_get.html" && echo true || echo false)"
assert "Champ message présent"        "$(grep -q 'name="message"' "$TMP_DIR/contact_get.html" && echo true || echo false)"
csrf1=$(fetch_csrf)
assert "CSRF token présent et non-vide" "$([ -n "$csrf1" ] && [ ${#csrf1} -ge 32 ] && echo true || echo false)"
log "CSRF lu : ${csrf1:0:16}…"

# ─── Test 2 : POST happy path — données valides ──────────────────────────
section "Test 2 — POST happy path (validation passe, redirect 302)"
fresh_session
csrf=$(fetch_csrf)
cat > "$TMP_DIR/body_valid.txt" <<EOF
csrf_token=$csrf&name=Test+Person&email=test%40example.com&message=Bonjour%20Sonia%2C%20ceci%20est%20un%20message%20de%20test%20e2e.
EOF
status=$(post_contact "$TMP_DIR/body_valid.txt")
log "Status : $status"
assert "Redirect 302 (envoi traité)" "$([ "$status" = "302" ] && echo true || echo false)"
# Note : success vs error dépend de la config SMTP — on ne peut pas trancher en e2e
# sans un mailcatcher. Le test valide juste que la chaîne de validation passe.

# ─── Test 3 : POST sans CSRF — refus ─────────────────────────────────────
section "Test 3 — POST sans CSRF (sécurité)"
fresh_session
fetch_csrf > /dev/null
cat > "$TMP_DIR/body_nocsrf.txt" <<'EOF'
name=Test+Person&email=test%40example.com&message=Bonjour%20Sonia%2C%20ceci%20est%20un%20message%20de%20test%20suffisant.
EOF
status=$(post_contact "$TMP_DIR/body_nocsrf.txt")
assert "Redirect 302 (refus traité)" "$([ "$status" = "302" ] && echo true || echo false)"
# Suivre la redirection, vérifier que le message d'erreur est affiché
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
# Le message exact est dans contact.error → "Une erreur est survenue. Merci de réessayer." (FR)
assert "Message d'erreur affiché après redirect" \
    "$(echo "$err_html" | grep -qE 'alert--error|erreur|error' && echo true || echo false)"

# ─── Test 4 : POST avec mauvais CSRF — refus ─────────────────────────────
section "Test 4 — POST avec CSRF invalide (sécurité)"
fresh_session
fetch_csrf > /dev/null
cat > "$TMP_DIR/body_badcsrf.txt" <<'EOF'
csrf_token=deadbeef0000000000000000000000000000000000000000&name=Test&email=test%40example.com&message=Bonjour%20Sonia%2C%20ceci%20est%20un%20message%20de%20test.
EOF
status=$(post_contact "$TMP_DIR/body_badcsrf.txt")
assert "Redirect 302 (refus)" "$([ "$status" = "302" ] && echo true || echo false)"
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Message d'erreur affiché" \
    "$(echo "$err_html" | grep -qE 'alert--error|erreur|error' && echo true || echo false)"

# ─── Test 5 : POST avec champs vides — refus ─────────────────────────────
section "Test 5 — POST avec champs vides"
fresh_session
csrf=$(fetch_csrf)
cat > "$TMP_DIR/body_empty.txt" <<EOF
csrf_token=$csrf&name=&email=&message=
EOF
status=$(post_contact "$TMP_DIR/body_empty.txt")
assert "Redirect 302" "$([ "$status" = "302" ] && echo true || echo false)"
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Erreur affichée" \
    "$(echo "$err_html" | grep -qE 'alert--error' && echo true || echo false)"

# ─── Test 6 : POST avec email invalide — refus ───────────────────────────
section "Test 6 — POST avec email invalide"
fresh_session
csrf=$(fetch_csrf)
cat > "$TMP_DIR/body_bademail.txt" <<EOF
csrf_token=$csrf&name=Test+Person&email=pas-un-email&message=Bonjour%20Sonia%2C%20ceci%20est%20un%20message%20suffisant.
EOF
status=$(post_contact "$TMP_DIR/body_bademail.txt")
assert "Redirect 302" "$([ "$status" = "302" ] && echo true || echo false)"
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Erreur affichée" \
    "$(echo "$err_html" | grep -qE 'alert--error' && echo true || echo false)"

# ─── Test 7 : POST avec message trop court — refus ───────────────────────
section "Test 7 — POST avec message trop court (<10 caractères)"
fresh_session
csrf=$(fetch_csrf)
cat > "$TMP_DIR/body_short.txt" <<EOF
csrf_token=$csrf&name=Test&email=test%40example.com&message=hi
EOF
status=$(post_contact "$TMP_DIR/body_short.txt")
assert "Redirect 302" "$([ "$status" = "302" ] && echo true || echo false)"
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Erreur affichée" \
    "$(echo "$err_html" | grep -qE 'alert--error' && echo true || echo false)"

# ─── Test 8 : POST avec injection CRLF dans email — refus ────────────────
section "Test 8 — POST tentative d'injection CRLF (header injection)"
fresh_session
csrf=$(fetch_csrf)
# email avec %0D%0A = CRLF + Bcc:attaquant@evil.com
# Doit être rejeté par hasHeaderInjection() même si filter_var l'avait laissé passer
cat > "$TMP_DIR/body_crlf.txt" <<EOF
csrf_token=$csrf&name=Test&email=test%40example.com%0D%0ABcc%3A+attaquant%40evil.com&message=Bonjour%20Sonia%2C%20ceci%20est%20un%20test%20suffisamment%20long.
EOF
status=$(post_contact "$TMP_DIR/body_crlf.txt")
assert "Redirect 302" "$([ "$status" = "302" ] && echo true || echo false)"
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Erreur affichée (rejet CRLF)" \
    "$(echo "$err_html" | grep -qE 'alert--error' && echo true || echo false)"

# ─── Test 9 : POST avec injection CRLF dans nom — refus ──────────────────
section "Test 9 — POST tentative d'injection CRLF dans le nom"
fresh_session
csrf=$(fetch_csrf)
cat > "$TMP_DIR/body_crlf_name.txt" <<EOF
csrf_token=$csrf&name=Test%0D%0ABcc%3A+evil%40evil.com&email=test%40example.com&message=Bonjour%20Sonia%2C%20ceci%20est%20un%20test%20suffisamment%20long.
EOF
status=$(post_contact "$TMP_DIR/body_crlf_name.txt")
assert "Redirect 302" "$([ "$status" = "302" ] && echo true || echo false)"
err_html=$(curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" "$BASE_URL/contact")
assert "Erreur affichée (rejet CRLF nom)" \
    "$(echo "$err_html" | grep -qE 'alert--error' && echo true || echo false)"

# ─── Bilan ───────────────────────────────────────────────────────────────
echo
echo -e "${BOLD}─── Bilan ─────────────────────────────────────────${RESET}"
TOTAL=$((PASS + FAIL))
if [ "$FAIL" -eq 0 ]; then
    echo -e "  ${GREEN}✓ $PASS / $TOTAL tests passent${RESET}"
else
    echo -e "  ${RED}✗ $FAIL / $TOTAL tests échouent${RESET}  (${GREEN}$PASS pass${RESET})"
fi
echo

exit "$FAIL"
