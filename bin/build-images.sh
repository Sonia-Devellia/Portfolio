#!/usr/bin/env bash
#
# Génère les variantes AVIF de chaque WebP du dossier public/assets/images.
#
# Pré-requis (macOS) :
#   brew install libavif imagemagick
#
# Usage : bin/build-images.sh
#
# Sortie : .avif aux côtés de chaque .webp ; ignore les fichiers déjà présents
# et à jour (comparaison mtime).

set -euo pipefail

cd "$(dirname "$0")/.."

ASSETS_DIRS=(
    "public/assets/images"
    "public/assets/img"
)

# ─── Outils ──────────────────────────────────────────────────────────────
if command -v avifenc >/dev/null 2>&1; then
    encoder="avifenc"
elif command -v magick >/dev/null 2>&1; then
    encoder="magick"
else
    echo "❌ Aucun encoder AVIF trouvé."
    echo "   Installe : brew install libavif    (recommandé, le plus rapide)"
    echo "   ou        : brew install imagemagick"
    exit 1
fi

echo "→ Encoder utilisé : ${encoder}"
echo ""

# ─── Encode un fichier source en AVIF ────────────────────────────────────
encode_avif() {
    local src="$1"
    local dst="$2"

    if [[ "$encoder" == "avifenc" ]]; then
        # avifenc lit WebP/PNG/JPEG. Qualité 70 = excellent compromis poids/qualité.
        avifenc --speed 6 --min 20 --max 35 "$src" "$dst" >/dev/null 2>&1
    else
        magick "$src" -quality 70 "$dst"
    fi
}

# ─── Boucle principale ───────────────────────────────────────────────────
generated=0
skipped=0

for dir in "${ASSETS_DIRS[@]}"; do
    [[ -d "$dir" ]] || continue

    while IFS= read -r src; do
        # Sortie : remplace .webp / .png / .jpg / .jpeg par .avif
        dst="${src%.*}.avif"

        # Skip si déjà présent et plus récent que la source
        if [[ -f "$dst" && "$dst" -nt "$src" ]]; then
            ((skipped++))
            continue
        fi

        echo "  → $dst"
        encode_avif "$src" "$dst"
        ((generated++))

    done < <(find "$dir" -type f \( -name "*.webp" -o -name "*.png" -o -name "*.jpg" -o -name "*.jpeg" \))
done

echo ""
echo "✓ ${generated} fichier(s) généré(s), ${skipped} ignoré(s) (à jour)."
