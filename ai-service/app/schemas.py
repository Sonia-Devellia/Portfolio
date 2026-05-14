"""
Schemas Pydantic — contrat strict entre le LLM et le client PHP.

Le LLM doit retourner EXACTEMENT cette structure, sinon le fallback déclenche.
Cette contrainte oblige le modèle à raisonner de manière structurée et nous
permet de garantir le format au consommateur (PHP).
"""
from __future__ import annotations

from enum import Enum
from typing import Literal

from pydantic import BaseModel, Field, field_validator


class ProjectType(str, Enum):
    """Familles de projets identifiables depuis un brief."""

    SHOWCASE = "showcase"        # Site vitrine, landing, portfolio
    MVP = "mvp"                  # Produit web complet (auth, BDD, backoffice)
    AI_INTEGRATION = "ai"        # Intégration LLM, classifieur, agent
    REFACTOR = "refactor"        # Reprise / refonte d'un code existant
    AUDIT = "audit"              # Audit technique 5 jours, plan d'action
    LONG_MISSION = "mission"     # Mission longue, embed équipe, CDI
    TRAINING = "training"        # Formation IA en entreprise
    OUT_OF_SCOPE = "out_of_scope"  # Hors périmètre (mobile natif, design pur, etc.)


class Complexity(str, Enum):
    """Estimation grossière de complexité — pilote le ranging prix."""

    S = "S"     # 1-2 semaines équivalent freelance
    M = "M"     # 3-6 semaines
    L = "L"     # 7-12 semaines
    XL = "XL"   # > 12 semaines ou mission longue


class CollabMode(str, Enum):
    """Modes de collaboration proposés par Sonia (cf. /tarifs)."""

    FREELANCE_FIXED = "freelance_fixed"   # Forfait projet, scope défini
    FREELANCE_DAILY = "freelance_daily"   # Régie au TJM, scope évolutif
    CDI_FULL = "cdi_full"                 # CDI remote 35h
    CDI_HALF = "cdi_half"                 # CDI mi-temps 17h30
    TRAINING = "training"                 # Formation IA
    REFER_ELSEWHERE = "refer_elsewhere"   # Pas pour Sonia, orienter ailleurs


class PriceRange(BaseModel):
    """Fourchette de prix indicative, EUR HT."""

    min_eur: int = Field(ge=0, description="Borne basse incluse")
    max_eur: int = Field(ge=0, description="Borne haute incluse")
    unit: Literal["total", "per_day", "per_month"] = Field(
        description="Unité : total (forfait), per_day (TJM), per_month (CDI)"
    )

    @field_validator("max_eur")
    @classmethod
    def _max_gte_min(cls, v: int, info) -> int:
        if "min_eur" in info.data and v < info.data["min_eur"]:
            raise ValueError("max_eur doit être >= min_eur")
        return v


class AnalyzeRequest(BaseModel):
    """Payload d'entrée envoyé par le PHP."""

    brief: str = Field(min_length=20, max_length=5000)
    lang: Literal["fr", "en"] = "fr"


class AnalyzeResponse(BaseModel):
    """
    Sortie structurée — contrat avec le client PHP.

    Le LLM remplit cette structure. En cas d'échec (timeout, JSON malformé,
    refus du modèle), le fallback déterministe remplit les mêmes champs.
    """

    project_type: ProjectType
    complexity: Complexity
    price: PriceRange
    risks: list[str] = Field(min_length=1, max_length=5)
    recommended_mode: CollabMode
    confidence: float = Field(ge=0.0, le=1.0)
    reasoning: str = Field(min_length=20, max_length=500)

    # Méta-données d'observabilité — affichées dans le panneau "sous le capot"
    is_fallback: bool = False
    prompt_version: str = "?"
    model: str = "?"
    tokens_in: int = 0
    tokens_out: int = 0
    latency_ms: int = 0
