# Product

## Register

brand

## Users

Two profiles, equal weight.

**CTO / Lead tech** — evaluates technical credibility. They scan for architecture decisions, code quality signals, stack depth. They want to see that Sonia thinks in systems, not just features. They've been burned by generalist freelancers before.

**Solo founder** — evaluates fit and understanding. They need to feel that Sonia gets the product, the constraints, the business logic. They don't have a tech team; they're betting on one person. What they fear most: being sold to, not understood.

Both arrive skeptical. Both need to leave convinced before the first message is sent.

## Product Purpose

A freelance portfolio whose primary job is conversion: turn a skeptical visitor into someone who writes an email. Secondary job: be the live proof of the craft it advertises. If the portfolio itself is imprecise or generic, the argument collapses.

Success = a qualified contact request from someone with a real mission (not a fishing enquiry). The design should pre-qualify: a visitor who doesn't value clean, purposeful work should self-select out before reaching the form.

## Brand Personality

précise · affirmée · silencieuse

**Précise** — no spare word, no spare pixel. Every decision is deliberate. Imprecision would undermine the entire proposition.

**Affirmée** — confident without performing confidence. No superlatives, no "passionate about", no apology for the atypical background. The background is an asset; treat it as one.

**Silencieuse** — the design doesn't ask for attention. It earns it through restraint. White space is not emptiness; it's editorial control. The work speaks; the interface stays quiet.

## Anti-references

Explicit rejections — if any of these patterns appear, rework:

- **Webflow purple-gradient templates**: animated sections, particle heroes, scroll-triggered everything, neon-on-dark aesthetic. The opposite of precision.
- **Generic Malt/LinkedIn profile**: white background, Inter 16px, left-aligned text, no visual identity. Zero differentiation.
- **Student portfolio with timeline + Flaticon icons**: decorative timelines, icon sets that don't belong to the brand, "my journey" framing. Too apologetic.
- **Ultra-typographic Parisian creative agency**: conceptual, obscure, prioritizes aesthetic posturing over communication. Too far from legibility.
- **SaaS mega-hero with moving particles**: animated gradient backgrounds, floating elements, viewport-height hero with 4 words and a video. Built for press, not for prospects.
- **Bootstrap card grids with heavy shadows and rounded blue buttons**: default-look UI, zero craft signal. Looks outsourced.

The design must be immediately distinguishable from all of these without being contrarian for its own sake.

## Design Principles

**1. The portfolio is the argument.** Every code architecture decision, every spacing choice, every interaction is a live demonstration of the craft being sold. Sloppiness anywhere is a credibility hole.

**2. Restraint as signal.** A design that doesn't beg for attention communicates confidence. Silence — in copy, in color, in motion — says more than elaboration. Add only what earns its place.

**3. Two lenses, one surface.** Every element passes both the CTO's technical lens (is this architected well?) and the founder's product lens (does this person understand what I'm building?). A section that works for only one fails.

**4. The atypical background is the edge.** Former entrepreneur + landscape designer → developer is not a weakness to explain away. It's a rare ability to hold client, product, and code in the same frame. Surface it with confidence, not defensiveness.

**5. Pre-qualify, don't sell.** The portfolio should repel the wrong prospect as efficiently as it attracts the right one. Directness in copy, precision in design, no inflated claims — someone looking for the cheapest option should feel this isn't their site.

## Accessibility & Inclusion

WCAG 2.1 AA minimum. Known requirements:
- Full keyboard navigation
- Focus visible on all interactive elements
- Colour contrast ratios per AA spec (already validated in design system for text/text-2; text-3 limited to decorative/large text only)
- `prefers-reduced-motion` respected in JS animations (IntersectionObserver observer)
- `prefers-color-scheme` respected for initial theme (light/dark toggle with localStorage persistence)
- Bilingual FR/EN — hreflang, language-appropriate phrasing (not literal translation)
- Screen reader announcements for form states (role="alert" on success/error)
