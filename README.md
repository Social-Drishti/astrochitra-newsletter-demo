# AstroChitra — Monthly Wisdom Newsletter Template

An expanded, impact-first HTML newsletter template for AstroChitra. It includes
Rashifal, a Guruji message, a featured Mantra of the Month, a high-visibility
Free Patrika banner, planetary transits, festivals, tips and more.

## Files

- `index.html` — the newsletter. Open it in any browser to preview.
- `assets/astrochitra-logo.png` — logo used in the header and footer.
- `assets/bg-parchment.png` — parchment texture used as the page background.
- `assets/Guruji2.jpg` — Guruji's photo used in the message section.
- `assets/event_placeholder.png` — placeholder used for the Patrika banner, festival card and blog spotlight.
- `assets/person_placeholder.png` — placeholder used for the testimonial avatar.

## Editing each month

Everything lives in plain HTML/CSS in `index.html` — no build step needed.
Open it in a text editor and update the sections top to bottom:

1. **Header** — edition pill (e.g. "July 2026 Edition") and subtitle.
2. **Guruji's Message** — swap the single powerful sentence, the note and the photo.
3. **Free Patrika Banner** — update month, value and download link.
4. **Rashifal** — replace the 12 zodiac predictions, lucky colours and numbers.
5. **Mantra of the Month** — update the Sanskrit mantra, meaning and chant count.
6. **Planetary Transits** — update planets, signs, effects and trend arrows.
7. **Festivals & Panchang** — update dates and observances.
8. **Tips & Information** — update the four tip cards (Health, Wealth, Love, Spirituality).
9. **Myth vs Fact / Remedy / Did You Know** — swap the monthly tidbits.
10. **Blog Spotlight / Testimonial** — update article, image and quote.
11. **CTA banner** and **Footer** — usually stay the same month to month.

Each section is wrapped in an HTML comment like `<!-- ============ HEADER ... -->`
so you can find things quickly with Ctrl/Cmd+F.

## Two things to add when you're ready

- **Real "AstroChitra" font file**: the template already points to
  `/AstroChitra.woff2` (same path as your live site), so if you host this
  page on the astrochitra.com domain the real display font will load
  automatically. Elsewhere it gracefully falls back to Cormorant Garamond.
- **Replace placeholder images**: swap `assets/event_placeholder.png` and
  `assets/person_placeholder.png` with real photos when available. The Guruji
  photo can be replaced by putting a new image in `assets/` and updating the
  `src="assets/Guruji2.jpg"` path.

## Sending as an email

This template uses modern CSS (flexbox/grid) for easy editing and looks
best viewed as a web page or attached/linked HTML. If you plan to paste it
into an email service (Mailchimp, etc.), some older email clients render
flexbox/grid inconsistently — let me know and I can produce a table-based
version built for maximum email-client compatibility.
