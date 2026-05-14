"""
Fallback déterministe — classification par règles si Claude est indisponible.

Garantit que /analyze retourne TOUJOURS un AnalyzeResponse valide, même si :
- l'API Claude est down
- le quota est dépassé
- le modèle renvoie un JSON invalide
- le timeout HTTP expire

C'est la traduction littérale de la promesse "fallback déterministe" du case study triage :
un système IA en production doit pouvoir être coupé sans casser l'application.
"""
from __future__ import annotations

import re

from .schemas import (
    AnalyzeResponse,
    CollabMode,
    Complexity,
    PriceRange,
    ProjectType,
)

# ─── Lexiques de classification — utilisés par mot-clé regex insensible casse ─

KEYWORDS_TRAINING = [
    "formation", "atelier", "session", "former mon équipe", "former l'équipe",
    "training", "workshop", "upskill",
]

KEYWORDS_CDI_FULL = [
    "cdi", "embauche", "embaucher", "temps plein", "full-time", "permanent",
    "salarié", "recrutement",
]

KEYWORDS_CDI_HALF = [
    "mi-temps", "temps partiel", "part-time", "50%", "half-time", "17h30",
]

KEYWORDS_AI = [
    "llm", "ia", "intelligence artificielle", "ai", "claude", "openai",
    "chatgpt", "gpt", "mistral", "agent", "classifieur", "embedding", "rag",
    "prompt", "fine-tuning",
]

KEYWORDS_AUDIT = [
    "audit", "diagnostic", "expertise", "revue de code", "code review",
    "reprise de code", "remise en état",
]

KEYWORDS_REFACTOR = [
    "refonte", "migration", "modernisation", "réécriture", "refactor",
    "rebuild", "ancien site", "wordpress vers",
]

KEYWORDS_SHOWCASE = [
    "site vitrine", "landing", "page d'atterrissage", "site internet présentation",
    "showcase",
]

KEYWORDS_MVP = [
    "mvp", "prototype", "application web", "saas", "plateforme", "backoffice",
    "espace client", "appli métier", "logiciel sur mesure",
]

KEYWORDS_OUT_OF_SCOPE = [
    "ios natif", "android natif", "swift", "kotlin", "design from scratch",
    "création de logo", "identité visuelle complète",
]


def _matches_any(text: str, keywords: list[str]) -> bool:
    """True si au moins un mot-clé apparaît (insensible à la casse)."""
    pattern = r"\b(?:" + "|".join(re.escape(k) for k in keywords) + r")\b"
    return bool(re.search(pattern, text, re.IGNORECASE))


def _detect_project_type(brief: str) -> ProjectType:
    """Routage par priorité de spécificité."""
    if _matches_any(brief, KEYWORDS_OUT_OF_SCOPE):
        return ProjectType.OUT_OF_SCOPE
    if _matches_any(brief, KEYWORDS_TRAINING):
        return ProjectType.TRAINING
    if _matches_any(brief, KEYWORDS_AUDIT):
        return ProjectType.AUDIT
    if _matches_any(brief, KEYWORDS_REFACTOR):
        return ProjectType.REFACTOR
    if _matches_any(brief, KEYWORDS_AI):
        return ProjectType.AI_INTEGRATION
    if _matches_any(brief, KEYWORDS_CDI_FULL) or _matches_any(brief, KEYWORDS_CDI_HALF):
        return ProjectType.LONG_MISSION
    if _matches_any(brief, KEYWORDS_SHOWCASE):
        return ProjectType.SHOWCASE
    if _matches_any(brief, KEYWORDS_MVP):
        return ProjectType.MVP
    # Défaut prudent : MVP de complexité M, le cas le plus fréquent
    return ProjectType.MVP


def _detect_complexity(brief: str, ptype: ProjectType) -> Complexity:
    """Estimation grossière par longueur + signaux."""
    length = len(brief)

    # Signaux explicites de scope large
    if any(w in brief.lower() for w in ["plusieurs mois", "année", "long terme", "scale", "scaling"]):
        return Complexity.L
    if ptype == ProjectType.LONG_MISSION:
        return Complexity.XL
    if ptype == ProjectType.AUDIT or ptype == ProjectType.TRAINING:
        return Complexity.S

    # Heuristique longueur du brief
    if length < 200:
        return Complexity.S
    if length < 600:
        return Complexity.M
    return Complexity.L


def _price_for(ptype: ProjectType, complexity: Complexity) -> PriceRange:
    """Table de prix indicative — alignée sur le prompt LLM pour cohérence."""
    table: dict[tuple[ProjectType, Complexity], tuple[int, int, str]] = {
        (ProjectType.SHOWCASE, Complexity.S):   (6_000,  12_000, "total"),
        (ProjectType.SHOWCASE, Complexity.M):   (12_000, 24_000, "total"),
        (ProjectType.MVP,      Complexity.M):   (24_000, 50_000, "total"),
        (ProjectType.MVP,      Complexity.L):   (50_000, 100_000, "total"),
        (ProjectType.AI_INTEGRATION, Complexity.S): (12_000, 24_000, "total"),
        (ProjectType.AI_INTEGRATION, Complexity.M): (24_000, 60_000, "total"),
        (ProjectType.AI_INTEGRATION, Complexity.L): (60_000, 120_000, "total"),
        (ProjectType.REFACTOR, Complexity.M):   (18_000, 40_000, "total"),
        (ProjectType.REFACTOR, Complexity.L):   (40_000, 80_000, "total"),
        (ProjectType.AUDIT,    Complexity.S):   (6_000,  9_000,  "total"),
        (ProjectType.TRAINING, Complexity.S):   (1_500,  4_000,  "total"),
        (ProjectType.LONG_MISSION, Complexity.XL): (600, 800, "per_day"),
    }
    if (ptype, complexity) in table:
        lo, hi, unit = table[(ptype, complexity)]
        return PriceRange(min_eur=lo, max_eur=hi, unit=unit)  # type: ignore[arg-type]

    # Défaut : TJM régie
    return PriceRange(min_eur=600, max_eur=800, unit="per_day")


def _detect_mode(brief: str, ptype: ProjectType) -> CollabMode:
    """Mode de collaboration selon les signaux du brief."""
    if ptype == ProjectType.OUT_OF_SCOPE:
        return CollabMode.REFER_ELSEWHERE
    if ptype == ProjectType.TRAINING:
        return CollabMode.TRAINING
    if _matches_any(brief, KEYWORDS_CDI_HALF):
        return CollabMode.CDI_HALF
    if _matches_any(brief, KEYWORDS_CDI_FULL):
        return CollabMode.CDI_FULL
    if ptype in (ProjectType.AUDIT, ProjectType.SHOWCASE, ProjectType.MVP, ProjectType.AI_INTEGRATION, ProjectType.REFACTOR):
        return CollabMode.FREELANCE_FIXED
    return CollabMode.FREELANCE_DAILY


def _risks_for(ptype: ProjectType) -> list[str]:
    """Risques génériques par type — moins fins que le LLM mais factuels."""
    risks = {
        ProjectType.SHOWCASE:       ["Performances LCP si beaucoup de visuels", "Cohérence de la charte typographique"],
        ProjectType.MVP:            ["Scope non figé entre cadrage et build", "Intégrations tierces souvent sous-estimées"],
        ProjectType.AI_INTEGRATION: ["Eval set indispensable avant promesse de précision", "Budget tokens à plafonner contractuellement"],
        ProjectType.REFACTOR:       ["Dette technique souvent plus lourde que prévue à l'inventaire", "Risque de régressions sans tests existants"],
        ProjectType.AUDIT:          ["Accès au code complet et à la BDD nécessaire en amont"],
        ProjectType.LONG_MISSION:   ["Modalités de fin de mission à acter dès le démarrage"],
        ProjectType.TRAINING:       ["Niveaux hétérogènes dans le groupe — calibrer le programme"],
        ProjectType.OUT_OF_SCOPE:   ["Hors périmètre — orienter vers une autre ressource adaptée"],
    }
    return risks.get(ptype, ["Cadrage initial à approfondir avant chiffrage ferme"])


def fallback_analyze(brief: str, lang: str = "fr") -> AnalyzeResponse:
    """
    Classification 100% déterministe à partir du brief.

    Utilisée :
    - quand l'API Claude est down ou indisponible
    - quand le modèle renvoie un JSON malformé après tous les retries
    - pour les tests qui ne veulent pas dépenser de tokens
    """
    ptype = _detect_project_type(brief)
    complexity = _detect_complexity(brief, ptype)
    price = _price_for(ptype, complexity)
    mode = _detect_mode(brief, ptype)
    risks = _risks_for(ptype)

    reasoning = (
        f"Classification déterministe (sans LLM) basée sur les mots-clés du brief. "
        f"Type détecté : {ptype.value}, complexité {complexity.value}."
        if lang == "fr" else
        f"Deterministic classification (no LLM) based on brief keywords. "
        f"Detected type: {ptype.value}, complexity {complexity.value}."
    )

    return AnalyzeResponse(
        project_type=ptype,
        complexity=complexity,
        price=price,
        risks=risks,
        recommended_mode=mode,
        confidence=0.5,  # Le fallback ne se prétend jamais sûr de lui
        reasoning=reasoning,
        is_fallback=True,
        prompt_version="fallback",
        model="rules-v1",
        tokens_in=0,
        tokens_out=0,
        latency_ms=0,
    )
