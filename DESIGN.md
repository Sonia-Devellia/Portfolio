---
name: Sonia Habibi — Portfolio
description: Editorial typographic system for a freelance full-stack developer portfolio
colors:
  press-white: "#ffffff"
  warm-newsprint: "#f7f6f4"
  old-linen: "#f0eeeb"
  press-ink: "#111110"
  aged-lead: "#5a5956"
  studio-slate: "#9a9895"
  available-green: "#0f6e56"
  available-green-bg: "#e1f5ee"
  available-dot: "#1d9e75"
  tag-php-bg: "#e6f1fb"
  tag-php-txt: "#185fa5"
  tag-python-bg: "#eaf3de"
  tag-python-txt: "#3b6d11"
  tag-js-bg: "#faeeda"
  tag-js-txt: "#854f0b"
  tag-ai-bg: "#eeedfe"
  tag-ai-txt: "#534ab7"
  tag-css-bg: "#faece7"
  tag-css-txt: "#993c1d"
  tag-misc-bg: "#f1efe8"
  tag-misc-txt: "#5f5e5a"
typography:
  display:
    fontFamily: "'DM Sans', system-ui, sans-serif"
    fontSize: "clamp(48px, 7vw, 88px)"
    fontWeight: 400
    lineHeight: 1.02
    letterSpacing: "-0.035em"
  headline:
    fontFamily: "'DM Sans', system-ui, sans-serif"
    fontSize: "clamp(36px, 5vw, 56px)"
    fontWeight: 500
    lineHeight: 1.15
  title:
    fontFamily: "'DM Sans', system-ui, sans-serif"
    fontSize: "clamp(26px, 3vw, 36px)"
    fontWeight: 500
    lineHeight: 1.2
  body:
    fontFamily: "'DM Sans', system-ui, sans-serif"
    fontSize: "15px"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "'DM Sans', system-ui, sans-serif"
    fontSize: "11px"
    fontWeight: 400
    lineHeight: 1.2
    letterSpacing: "0.08em"
  serif-accent:
    fontFamily: "'DM Serif Display', Georgia, serif"
    fontSize: "22px"
    fontWeight: 400
    lineHeight: 1.2
rounded:
  sm: "6px"
  md: "10px"
  lg: "14px"
  pill: "999px"
components:
  button-primary:
    backgroundColor: "{colors.press-ink}"
    textColor: "{colors.press-white}"
    rounded: "{rounded.pill}"
    padding: "11px 22px"
  button-primary-hover:
    backgroundColor: "{colors.press-ink}"
    textColor: "{colors.press-white}"
  button-outline:
    backgroundColor: "transparent"
    textColor: "{colors.aged-lead}"
    rounded: "{rounded.pill}"
    padding: "11px 22px"
  button-outline-hover:
    backgroundColor: "{colors.warm-newsprint}"
    textColor: "{colors.press-ink}"
  button-ghost:
    backgroundColor: "transparent"
    textColor: "{colors.aged-lead}"
    rounded: "{rounded.pill}"
    padding: "11px 10px"
  tag:
    rounded: "{rounded.pill}"
    padding: "3px 8px"
  service-card:
    backgroundColor: "{colors.press-white}"
    padding: "32px 28px 28px"
  service-card-hover:
    backgroundColor: "{colors.warm-newsprint}"
  project-card:
    backgroundColor: "{colors.press-white}"
    rounded: "{rounded.lg}"
    padding: "20px 22px 22px"
---

# Design System: Sonia Habibi — Portfolio

## 1. Overview

**Creative North Star: "The Press Room"**

This system is modeled on the functional beauty of professional print — the ordered grid of a newspaper layout, the authority of ink on paper, the discipline of a designer who removes before adding. Nothing here is decorative. The whitespace is load-bearing. The 1px borders are structural. The DM Serif Display italic appears in exactly four positions across the entire interface, and its rarity gives each appearance genuine weight.

Depth is achieved without shadows. Surfaces stack tonally — from Press White (#ffffff) to Warm Newsprint (#f7f6f4) to Old Linen (#f0eeeb) — and are separated by hairline rules, not elevation. The result is a surface that reads as a printed sheet, not a floating app. The only color signal in the system is availability green, appearing once in the navigation badge. Its singularity is the point.

This system refuses four aesthetics by design: the Webflow gradient template (neon-on-dark, scroll-triggered everything, purple accents), the generic contractor profile (stark white, Inter 16px, no identity), the SaaS mega-hero (viewport-height hero with animated particles and four words), and the Bootstrap card grid (heavy drop shadows, rounded blue primary buttons). What it builds instead is quiet authority — the kind a studio director projects, not the kind a pitch deck performs.

**Key Characteristics:**
- Tonal depth through layered warm off-whites — no box-shadows anywhere
- DM Serif Display italic used sparingly as editorial punctuation, never as prose
- Pill buttons (999px radius) for all interactive elements; restrained radius on containers
- Six semantic tag color pairs, each mapped to a specific technology domain
- Single green signal (availability badge) — never reused for other semantic states
- Full light/dark theme via CSS custom properties, zero JavaScript dependency for theming

## 2. Colors: The Warm Ink Palette

A near-monochromatic system built from warm off-whites and warm blacks, with a single green accent reserved for one semantic use only. No blue links, no purple CTAs, no gradient anything.

### Primary
- **Available Green** (`#0f6e56` / `--avail: #0f6e56`): Used exclusively for the availability badge in the navigation. The only saturated color on any page surface. Its use is forbidden outside this single context. In dark mode: `#1d9e75`.
- **Available Dot** (`#1d9e75` / `--avail-dot`): The animated pulse indicator within the badge. Slightly lighter than Available Green to read on the badge background.
- **Available Green Background** (`#e1f5ee` / `--avail-bg`): Badge background. Also used for success alert backgrounds. In dark mode: `rgba(29, 158, 117, 0.12)`.

### Neutral
- **Press White** (`#ffffff` / `--bg`): Primary page background. Not pure white by intention — the system's warm neutrals make it read slightly warm in context. In dark mode replaced by Press Ink.
- **Warm Newsprint** (`#f7f6f4` / `--bg-soft`): Section alternates, card backgrounds, hover states on service cards. The primary tonal layer above Press White.
- **Old Linen** (`#f0eeeb` / `--bg-subtle`): Thumbnail placeholders, discrete hover backgrounds, focus ring shadow color. Lightest tonal step used for interaction feedback.
- **Press Ink** (`#111110` / `--text`): Primary text, button fills, icon strokes, active borders. Near-black with a fractional warm tint — never `#000000`. In dark mode: `#f0eeeb` (inverted role, becomes primary text on dark bg).
- **Aged Lead** (`#5a5956` / `--text-2`): Secondary text — descriptions, nav links, outline button text. In dark mode: `#a09e9b`.
- **Studio Slate** (`#9a9895` / `--text-3`): Tertiary — eyebrows, captions, timestamps, placeholder text. WCAG AA only at 18px+ or bold. Never for informational content at small sizes. In dark mode: `#6a6866`.

### Tag Color System
Six semantic pairs, technology-specific. Background tints are desaturated pastels; text colors are fully saturated and WCAG AA compliant on their respective backgrounds.

| Technology | Background | Text |
|-----------|------------|------|
| PHP | `#e6f1fb` (`--tag-blue-bg`) | `#185fa5` (`--tag-blue-txt`) |
| Python | `#eaf3de` (`--tag-green-bg`) | `#3b6d11` (`--tag-green-txt`) |
| JavaScript / JS | `#faeeda` (`--tag-amber-bg`) | `#854f0b` (`--tag-amber-txt`) |
| AI / LLM / Claude / OpenAI | `#eeedfe` (`--tag-purple-bg`) | `#534ab7` (`--tag-purple-txt`) |
| SCSS / CSS | `#faece7` (`--tag-coral-bg`) | `#993c1d` (`--tag-coral-txt`) |
| Other / misc | `#f1efe8` (`--tag-gray-bg`) | `#5f5e5a` (`--tag-gray-txt`) |

All six pairs invert to saturated dark backgrounds with light text in dark mode — the pairing logic is maintained, the surface inverts.

**The One Signal Rule.** Available Green is the only saturated hue in the system. It means one thing: Sonia is available for work. Do not use green for success states, hover effects, active links, or any other semantic purpose. Use the alert background tint (`--avail-bg`) for success confirmation only.

**The No Invention Rule.** No new color values may be introduced. Every color decision is a choice between an existing token. If a new semantic state seems to need a new color, use a combination of existing tones, borders, and opacity instead.

## 3. Typography

**Display / Headline Font:** DM Sans (Google Fonts, variable: `ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500`)
**Editorial Accent Font:** DM Serif Display italic only (`ital@1`)

**Character:** DM Sans is a low-contrast humanist grotesque — warm enough to feel approachable at body sizes, neutral enough to disappear at large display sizes and let the words carry weight. DM Serif Display italic is used as punctuation: the logo wordmark, numbered service cards, the hero nameplate, WIP card labels. It never appears in prose, never in buttons, never in descriptions.

### Hierarchy
- **Display** (400 weight, `clamp(48px, 7vw, 88px)`, lh 1.02, ls -0.035em): Hero title only. Tight tracking at large sizes creates editorial compression. Used with `letter-spacing: -0.035em` to maintain optical density.
- **Headline** (500 weight, `clamp(36px, 5vw, 56px)`, lh 1.15): Internal page h1 titles (contact, projects list, project detail).
- **Title** (500 weight, `clamp(26px, 3vw, 36px)`, lh 1.2): Section h2 headings — services title, projects title, about title.
- **Card Title** (500 weight, `16–17px`, lh 1.3): Service card h3, project card h3.
- **Body** (400 weight, `15px`, lh 1.6): All running text, descriptions, paragraphs. Max line length: 52–56ch in hero sub, 32ch in service card descriptions.
- **Small** (400 weight, `13px`, lh 1.6): Secondary text — project card descriptions, button labels, link labels.
- **Label / Eyebrow** (400 weight, `11px`, ls 0.08em, uppercase): Eyebrow supertitles, tag text, form labels, nav availability badge, meta captions. UPPERCASE with wide tracking — the system's identifying micro-typographic signature.

**Serif Accent** (DM Serif Display, italic, 400 weight): Logo in nav (22px), service card numbers (32px), hero nameplate name (16px), WIP card label (32px). These four contexts only.

**The Press Room Rule.** DM Serif Display italic is punctuation, not prose. It appears in exactly four established positions. Adding it anywhere else — descriptions, headings, buttons, new card types — dilutes the system's restraint. When in doubt, DM Sans.

**The Tracking Rule.** Uppercase labels carry `letter-spacing: 0.08em`. Display sizes carry negative tracking (`-0.035em`). Body and small text carry neutral tracking or fractional positive (`0.005em` max). Never apply positive tracking to body text — it signals amateur typesetting.

## 4. Elevation

This system uses no box-shadows. Depth is conveyed entirely through two mechanisms: tonal layering (background tokens stepping from Press White → Warm Newsprint → Old Linen) and hairline structural borders (1px, either `--border` at 8% opacity or `--border-md` at 14% opacity).

The navigation achieves a subtle "frosted" quality via `backdrop-filter: blur(8px)` — the only use of blur in the system, serving a structural function (sticky nav separation) rather than a decorative one.

**The Flat-by-Lines Rule.** Every surface is at the same elevation at rest. Depth is in the tonal steps, not in shadows. If you are reaching for a `box-shadow`, use a `border` or a background-color step instead. The one exception is the focus ring — `box-shadow: 0 0 0 3px var(--bg-subtle)` — which is an accessibility treatment, not an elevation signal.

### Tonal Layer Vocabulary
- **Layer 0 — Press White** (`--bg`): Primary page background; project card bodies; nav background; form field backgrounds.
- **Layer 1 — Warm Newsprint** (`--bg-soft`): Projects section background; service card hover state; outline button hover fill; alert backgrounds.
- **Layer 2 — Old Linen** (`--bg-subtle`): Thumbnail placeholder backgrounds; focus ring shadow; interactive hover accents.
- **Structural Rules** (`--border` at 8% / `--border-md` at 14%): Hairlines between sections, around cards, under the nav, within the hero grid division.

## 5. Components

### Buttons

Full-pill shape (border-radius: 999px) throughout. No squared buttons anywhere. Three variants; never two primary buttons adjacent.

- **Shape:** Full pill (999px radius). Minimum height 44px (WCAG 2.5.5 touch target). Font: DM Sans, 13px, weight 400, tracking 0.005em.
- **Primary (`btn--dark`):** Background Press Ink (`--text`), text Press White (`--bg`), border Press Ink. Hover: opacity 0.88 (the text colour shows through slightly). Press feedback: `translateY(1px)`. This is the conversion CTA — appears once per decision zone.
- **Outline (`btn--outline`):** Transparent background, border `--border-md`, text Aged Lead. Hover: background Warm Newsprint, text Press Ink, border Aged Lead. Secondary actions and discovery CTAs.
- **Ghost (`btn--ghost`):** No background, no border, text Aged Lead. Padding reduced to `11px 10px`. Text-only link-action. For nav links and subtle contextual actions.
- **Small modifier (`btn--sm`):** Height 36px, padding `8px 16px`, font 11px, tracking 0.04em. Section-level CTAs ("See all →").
- **Large modifier (`btn--lg`):** Height 56px, padding `16px 28px`, font 15px. Not currently used but available for future hero variants.

### Tags (Technology Chips)

Small, uppercase-adjacent, color-coded by technology domain. Not interactive in current V1.

- **Shape:** Full pill (999px radius), padding `3px 8px`, font 11px, weight 400.
- **Color:** One of six semantic pairs (see Colors §2 tag table). Color is assigned by technology, not by preference. The `tagColor()` PHP helper enforces the mapping.
- **Usage:** Appear in project cards (above title), service cards (below description), and hero (stack declaration). Never appear as standalone decorative elements.

**The Mapping Rule.** Tags follow a fixed technology-to-color map. PHP → blue, Python → green, JavaScript → amber, AI/LLM/Claude → purple, SCSS/CSS → coral, other → gray. Do not use tag colors decoratively or reassign them to different technologies for visual variety.

### Service Cards — The Newspaper Grid

The signature structural component. Three service cards form a continuous grid where borders are shared, not duplicated. The grid container carries `border-top` and `border-left`; each card carries `border-right` and `border-bottom`. No gap. No corner radius on the cards.

- **At rest:** Background Press White, no hover signal.
- **Hover:** Background transitions to Warm Newsprint (`var(--bg-soft)`). The italic serif number transitions from Studio Slate to Press Ink. Transition: 240ms `cubic-bezier(0.2, 0.8, 0.2, 1)`.
- **Number accent:** DM Serif Display italic, 32px, Studio Slate at rest. This is the primary editorial signature of the component.
- **Description max-width:** `32ch` — enforced by CSS to maintain legibility in narrow columns.
- **Mobile:** Grid collapses to single column; shared border logic reflows.

This grid pattern should be used for any 3-column feature list. Do not convert it to individual cards with gaps and border-radius.

### Project Cards

Rounded container (14px radius) with a thumbnail zone, tag strip, title, description, and action links. Exists in standard and wide (`span 2`) variants.

- **Container:** Background Press White, border `1px solid --border`, radius 14px, overflow hidden. Hover: border transitions to `--border-md`, `translateY(-3px)`. WIP variant: border-style `dashed`.
- **Thumbnail zone:** Fixed height (180px standard, 220px wide). Background Old Linen as placeholder. Image has `object-fit: cover`; on card hover, image scales to 1.04x.
- **Body padding:** `20px 22px 22px`.
- **Link treatment:** `font-weight: 500`, `color: --text`. On hover: color transitions to Aged Lead, `translateX(2px)` — the arrow effect.

### Form Fields

- **Label:** 11px, uppercase, tracking 0.08em, Studio Slate. Matches eyebrow typographic register.
- **Field:** Background Press White, border `1px solid --border`, radius 6px, height 48px (WCAG minimum). Font DM Sans 15px.
- **Hover (unfocused):** Border transitions to `--border-md`.
- **Focus (`focus-visible`):** Border becomes Press Ink; `box-shadow: 0 0 0 3px var(--bg-subtle)`. The double-border treatment is the system's focus signature — do not alter it.
- **Placeholder:** Studio Slate (`--text-3`), opacity 1.
- **Textarea:** Minimum height 140px, vertical resize only.

### Navigation

Sticky, 60px height, `backdrop-filter: blur(8px)`, separated from content by `1px solid --border`.

- **Logo:** DM Serif Display italic, 22px, Press Ink. The only serif element in the nav.
- **Links:** 13px, Aged Lead at rest, Press Ink on hover. No underline, transition 150ms.
- **Availability badge:** Full pill, 11px uppercase, Available Green text on Available Green Background. Animated 5px dot (`pulse` keyframe, 2s, opacity 1→0.4→1).
- **Dark mode toggle:** 32px circle, border `--border-md`, Studio Slate icon. Hover: Warm Newsprint background.
- **Primary CTA:** `btn--dark` in mobile nav only (hidden on desktop, visible on mobile via responsive rules).

### Availability Badge — Signature Component

The single most recognizable UI element. A small pill in the navigation carrying the only saturated color in the system.

```
[ • Disponible ]
```

- Text: 11px, tracking normal, Available Green (`--avail`)
- Background: Available Green Background (`--avail-bg`), border-radius 20px, padding `4px 10px`
- Dot: 5px circle, Available Dot color (`--avail-dot`), `animation: pulse 2s infinite`
- Position: Desktop nav, before language switcher and theme toggle

**Never reuse the green signal.** This badge is the only place this color appears. Its presence is a credibility signal about Sonia's status — diluting it with secondary green accents elsewhere destroys its meaning.

## 6. Do's and Don'ts

### Do:
- **Do** use the hairline border system (`1px solid var(--border)`) to separate content zones. Structure comes from lines, not shadows.
- **Do** use the service card's shared-border newspaper grid for any 3-column feature section. Individual gapped cards with rounded corners break the editorial register.
- **Do** tonal-layer backgrounds (Press White → Warm Newsprint → Old Linen) to create depth without elevation.
- **Do** use DM Serif Display italic only in its four established positions: nav logo, service card numbers, hero nameplate name, and WIP placeholder labels.
- **Do** assign technology tags by the fixed color map (PHP→blue, Python→green, JS→amber, AI→purple, CSS→coral, misc→gray). The map is the system — don't reassign for visual variety.
- **Do** maintain the double-border focus treatment: `border-color: var(--text)` + `box-shadow: 0 0 0 3px var(--bg-subtle)`. Do not replace with a colored glow or outline.
- **Do** use pill buttons (border-radius 999px) for all interactive button elements. The pill is the system's interactive affordance shape.
- **Do** limit each decision zone to one `btn--dark` (primary CTA). A second dark button in the same zone destroys the hierarchy.
- **Do** apply negative letter-spacing (`-0.035em`) to display-size headings. Apply wide tracking (`0.08em`) to uppercase labels only.
- **Do** enforce `max-width` on body text — 52ch max in hero subtitles, 32ch in service card descriptions. Uncontrolled line lengths break the editorial feel.

### Don't:
- **Don't** use gradient text (`background-clip: text` with a gradient). Prohibited. Use Press Ink or Aged Lead.
- **Don't** use neon-on-dark aesthetics, purple gradients, or animated particle effects. These are the Webflow template anti-reference — the system is explicitly its opposite.
- **Don't** use Inter as the typeface. The system uses DM Sans. Using Inter produces the generic Malt profile aesthetic the system rejects.
- **Don't** use external icon libraries (Flaticon, Font Awesome, Heroicons packages). Inline SVG with `currentColor` only.
- **Don't** use decorative icon-plus-timeline milestone patterns. This is the student portfolio aesthetic the system rejects.
- **Don't** add a second color accent for "variety." The green signal is the only accent. A second color breaks the system's core logic.
- **Don't** use box-shadows for elevation. If you are writing `box-shadow` for anything other than the focus ring, stop and use a border or tonal background instead.
- **Don't** use Bootstrap-style heavy drop shadows (`box-shadow: 0 4px 16px rgba(0,0,0,0.2)`), rounded blue primary buttons, or dense card grids. These are the explicit Bootstrap anti-reference.
- **Don't** use DM Serif Display for descriptions, body text, button labels, or any new headings outside the four established positions. Its rarity is its power.
- **Don't** apply `letter-spacing` to body or small text. Only display sizes (negative) and uppercase labels (positive 0.08em) use non-zero tracking.
- **Don't** use color alone to convey status. Tags always carry text labels; the availability badge always carries text alongside the dot.
- **Don't** create new color tokens. Extend the system only by combining existing tokens, not by introducing new hex values.
- **Don't** use `!important`. Reorganize specificity instead.
