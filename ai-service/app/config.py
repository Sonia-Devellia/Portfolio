"""Configuration centralisée — variables d'environnement validées au boot."""
from __future__ import annotations

import os
from functools import lru_cache

from pydantic import Field
from pydantic_settings import BaseSettings, SettingsConfigDict


class Settings(BaseSettings):
    """Configuration de l'application — chargée depuis .env ou les vars d'environnement."""

    model_config = SettingsConfigDict(env_file=".env", env_file_encoding="utf-8", extra="ignore")

    # ─── Claude API ──────────────────────────────────────────────
    claude_api_key: str = Field(default="", description="Clé API Anthropic")
    claude_model: str = Field(default="claude-3-5-sonnet-20241022")

    # ─── Budget & timeouts ───────────────────────────────────────
    max_input_tokens: int = Field(default=2000, description="Brief tronqué au-delà")
    max_output_tokens: int = Field(default=600, description="Cap dur sur la sortie LLM")
    request_timeout_s: float = Field(default=20.0, description="Timeout HTTP côté API LLM")

    # ─── Sécurité ────────────────────────────────────────────────
    api_shared_secret: str = Field(
        default="",
        description="Secret partagé attendu dans le header X-Service-Token (anti-spam)",
    )

    # ─── Mode ────────────────────────────────────────────────────
    force_fallback: bool = Field(
        default=False,
        description="Pour les tests : force toujours le fallback déterministe",
    )


@lru_cache
def get_settings() -> Settings:
    """Settings singleton — chargé une fois au boot."""
    return Settings()


def claude_configured() -> bool:
    """True si on a une clé API valide → on peut appeler Claude."""
    return bool(get_settings().claude_api_key) and not get_settings().force_fallback
