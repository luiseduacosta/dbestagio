# Design Notes: Estágio ESS/UFRJ — Logo System

- Artifact id: `41aa3636`
- Source HTML path: `.kun-design/d4523f2e/41aa3636/v2.html`
- Design notes file: `.kun-design/d4523f2e/41aa3636/DESIGN.md`
- Current version: v2 (`.kun-design/d4523f2e/41aa3636/v2.html`)
- Previous version: v1 (`.kun-design/d4523f2e/41aa3636/v1.html`)
- Updated: 2026-10-19

## Screen Role

A single-page brand-system presentation for **Estágio ESS/UFRJ**, the internship opportunity board of the Escola de Serviço Social at UFRJ.

The page now acts as both a logo sheet and a light brand-application page:

- Introduces the brand with a visible H1 and concise context.
- Shows the primary vertical lockup on the institutional blue presentation tile.
- Shows the horizontal two-colour signature and a one-colour reproduction.
- Demonstrates the identity in a realistic excerpt of the mural product.
- Provides a working primary action to download the vector symbol and a secondary path into the digital application shown lower on the page.

## Visual Direction

The mark remains a monolithic **E** whose lower arm extends into a forward arrow. It reads as the brand initial while suggesting passage from study into supervised practice. The presentation is rectilinear, institutional, and data-aware rather than decorative.

Palette:

| Token | Value | Role |
| --- | --- | --- |
| `--accent` | `#0B5CAB` | Cobalt brand field, primary action, links |
| `--accent-deep` | `#084A8A` | Hover / pressed brand state |
| `--accent-soft` | `#E8F0FA` | Quiet selection and hover surfaces |
| `--canvas` | `#F4F7F9` | Cool paper background |
| `--surface` | `#FFFFFF` | Main surfaces |
| `--ink` | `#17222B` | Headings and primary text |
| `--muted` | `#52616D` | Metadata and supporting copy |
| `--success` | `#176B3A` on `#EAF5EE` | Open status, validation |
| `--warning` | `#8A4B00` on `#FFF4E5` | Closing soon, sample-data marker |
| `--danger` | `#B3261E` on `#FDECEA` | Closed status, error feedback |
| `--focus` | `#A15C00` | 3 px keyboard focus ring |

The added semantic colours break the previous one-hue monotony while keeping cobalt as the single brand accent. Every coloured status is paired with text, never colour alone. The wordmark and headings use zero or positive tracking and fixed bounded type sizes with breakpoint overrides.

Typography:

- **Display/UI:** Source Sans 3, with system sans-serif fallbacks.
- **Metadata/data:** IBM Plex Mono, with system monospace fallbacks.
- Page hierarchy: H1 40 px → section H2 26 px → application H3 24 px → body 16 px → metadata 9–13 px.
- Logo wordmark: 78 px desktop, 56 px tablet, 46 px mobile, 38 px in the short-height frame.

## Page Structure

1. **Header** — brand mini-lockup, section nav (`Marca`, `Versões`, `Aplicação`), compact `Baixar SVG` action.
2. **Hero introduction** — visible H1 `Estágio ESS/UFRJ`, supporting line, demonstration metrics (`2026.2`, 18 open offers, 62 vacancies), and the explicit sample-data tag.
3. **Primary tile** — vertical white lockup on `#0B5CAB`, with frame labels and a quieter descriptor.
4. **Assinaturas da marca** — horizontal two-colour signature and one-colour signature, each in a labelled specimen surface.
5. **A marca no mural de estágios** — one embedded product preview showing the logo in context, with 18 open offers, 62 vacancies, three named institutions, statuses, and deadlines. The section is explicitly marked as a preview; the data is illustrative.
6. **Footer** — brand attribution, internal section links, coordination email, copyright, and minimum digital size.

## Interactions and States

- **Baixar SVG** (header and hero) generates a standalone `estagio-ess-ufrj-simbolo.svg` vector via `Blob` and triggers a download.
- During download, the clicked button gets `aria-busy="true"`, shows `Gerando SVG…`, and uses the `.is-busy` state.
- A live `role="status"` line announces success with the filename, or a recoverable error message.
- **Ver aplicação no mural** scrolls to `#aplicacao`; the top navigation links to the three anchor sections.
- Focus-visible outlines are 3 px in the warm focus colour on all links, buttons, and tabbable elements.
- Hover states are defined for primary/secondary buttons, nav links, and footer links.
- `prefers-reduced-motion: reduce` disables smooth scrolling and transitions.

Relevant states covered: default, hover, focus-visible, busy, success, and error. The product preview includes open, closing-soon, and closed domain states so the visual language is demonstrated rather than only described.

## Responsive Behaviour

- **1440 px and above:** 1200 px content cap, full header/nav, primary lockup at 78 px.
- **1280 px:** reference composition; tagline and actions share the hero row; primary tile fits at 384 px high.
- **1024 px:** fluid two-column hero, 16 px gutters, 369 px primary tile.
- **768 px:** header remains single-row; hero remains two-column until 760 px; no horizontal overflow.
- **760 px and below:** nav wraps to its own row, hero stacks, primary tile reduces to 56 px wordmark, specimens stack, application preview becomes one column, and offer rows reflow into name + status/deadline pairs.
- **480 px and below:** full-width hero actions, 46 px wordmark, 300 px primary tile, 14 px page gutters.
- **620 px tall and below:** compact tile (220 px), 38 px wordmark, reduced label spacing; the complete primary lockup remains visible at 1280×480.
- The page does not use a fixed viewport height or `overflow: hidden`; vertical scrolling is expected on short frames and small screens.

Validated widths: 1440, 1280, 1024, 900, 768, 600, and 390 px. No horizontal scroll or clipping was detected.

## Accessibility

- `lang="pt-BR"` and meaningful `<title>`: `Estágio ESS/UFRJ — Marca e aplicação digital`.
- Skip link to the main content; one visible H1; section H2s and article H3s in order.
- Brand and signature SVGs are marked decorative where the adjacent text supplies the name; the masthead links have explicit brand labels.
- CTA and nav targets meet 40–44 px minimum height.
- Contrasts: white on cobalt 6.7:1; muted ink on canvas 6.0:1; success, warning, and danger combinations exceed 5.5:1.
- Focus rings measured at 3 px with 3 px offset.

## Assumptions and Handoff Notes

- The page is a standalone HTML artifact with inline CSS, inline SVG, and inline JavaScript. There are no external assets or dependencies.
- The 2026.2 metrics, institution names, vacancy counts, statuses, and deadlines are illustrative product data and are visibly identified as sample data. Replace them with the live mural query before production.
- The SVG download contains the vector symbol only. The wordmark is live text in the prototype; for production brand distribution, convert the final Source Sans 3 wordmark to outlines and package it as a print-ready vector lockup.
- The layout, `prefers-reduced-motion` fallback, and focus behaviour are ready to preserve in implementation.
- The page deliberately uses one cobalt brand accent plus neutral surfaces and semantic state colours. Do not turn the state colours into decorative accents.

## Version History

- **v2:** Applied the audit fixes to the v1 logo sheet: visible H1 and product context, functional download and secondary navigation, semantic palette, focus states, realistic mural data, and responsive/short-height refinements.
- **v1:** Initial logo showcase with primary tile, horizontal variant, and monochrome variant.
