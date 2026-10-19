# Estágio ESS/UFRJ — Design brief

> Canonical foundation for every screen and implementation pass in this project.
> Status: approved for screen design. Language: Brazilian Portuguese UI, English design notes.
> Source context: `mural/mural-listar.php`, `smarty/templates/muralestagios-listar.tpl`, `app.css`, `estagio.css`, and the 1280×800 web target.

## Product concept and audience

**Estágio ESS/UFRJ** is the internship opportunity board and coordination system for the Escola de Serviço Social at the Universidade Federal do Rio de Janeiro. It connects students looking for supervised internship placements with the coordination team, placement institutions, and faculty supervisors.

The product has two linked jobs:

1. **For students and public visitors:** understand which internship offers are open, compare institutions and conditions, open one offer, and submit an application without guessing what to do next.
2. **For the coordination team:** see the current offer cycle at a glance, publish and maintain offers, and review the candidates attached to each offer.

Primary audiences, in order:

- **Students of Serviço Social** — often on mobile, looking for a credible offer quickly and checking deadlines.
- **Coordination staff and administrators** — desktop-first, working through many records and periods.
- **Faculty supervisors and partner institutions** — occasional desktop visitors who need clear offer details and contact paths.

The brand promise is **institutional clarity**: this is a public-service tool, not a startup landing page. Every screen should make the current period, the offer status, the next action, and the way back unmistakable.

### Core journeys

- Public: **Mural → Oferta → Inscrição → confirmação → Oferta/Mural**
- Returning coordinator: **Entrar → Gestão do Mural → Oferta → inscritos**
- Escape path everywhere: return to the **Mural de Estágios** without losing context.

## Visual direction

### Mood

A well-kept public noticeboard crossed with an academic case file: calm, precise, human, and accountable. The interface should feel native to a university coordination office — editorial hierarchy, real data density, visible status language, and no decorative theatrics.

The visual signature is a **deep cobalt accent on a cool paper-white surface**, structured by a strong left rail and fine rules. It should read as a system that has been carefully maintained, not as a generic SaaS dashboard.

### Palette intent

Use one brand accent and a real neutral ramp. Do not introduce a second decorative accent. Status colours are semantic only.

| Role | Token | Value | Use |
| --- | --- | --- | --- |
| Brand / primary action | `--accent` | `#0B5CAB` | Primary buttons, active nav, links, focus ring |
| Accent hover / pressed | `--accent-strong` | `#084A8A` | Hover and pressed states |
| Accent wash | `--accent-soft` | `#E8F0FA` | Selected rail item, quiet emphasis |
| Canvas | `--canvas` | `#F4F7F9` | App background |
| Surface | `--surface` | `#FFFFFF` | Main content, rail, table surface |
| Ink | `--ink` | `#17222B` | Headings and primary text |
| Secondary ink | `--ink-muted` | `#52616D` | Supporting labels and metadata |
| Rule | `--line` | `#D7E0E7` | Dividers, table rules, input borders |
| Success | `--success` | `#176B3A` on `#EAF5EE` | Open / confirmed |
| Warning | `--warning` | `#8A4B00` on `#FFF4E5` | Closing soon / attention |
| Danger | `--danger` | `#B3261E` on `#FDECEA` | Closed / destructive action |

Contrast checks against the intended backgrounds: ink on canvas **15.2:1**, muted ink on canvas **6.0:1**, white on accent **6.7:1**, success **5.9:1**, warning **6.3:1**, danger **5.7:1**. Never place muted grey text on an accent fill. Never use pure `#000`. Never use cream/sand as the default canvas, purple-to-blue gradients, or glass effects.

### Typography intent

- **UI and body:** `Source Sans 3` with a system sans-serif fallback. It is open, highly legible in Portuguese, and feels institutional without looking bureaucratic.
- **Data accents:** `IBM Plex Mono` at 500 weight for record IDs, period labels, dates in dense tables, and numeric comparison columns. Use it sparingly; it is a signal, not a body font.
- **Scale:** 12 / 14 / 16 / 20 / 28 / 40 px. Body 16 px with 1.6 line-height; headings 1.15 line-height with tight tracking. Small uppercase labels at 12 px and 0.06em tracking.
- **Rules:** sentence case for headings and buttons; tabular numerals for counts; no more than two families; avoid all-caps paragraphs.

### Layout and motion personality

- **Rectilinear and legible.** Strong left alignment, a 12-column content grid, and thin rules instead of stacked shadowed cards.
- **Dense but breathable.** Desktop content uses the available width; tables and filters expand, while prose blocks stop at roughly 72 characters.
- **One persistent shell.** Header and navigation live outside the page content. No iframe, no nested menu inside the content, no duplicate document chrome.
- **Motion:** 140–180 ms ease-out for disclosure, drawer, hover, and focus feedback. No bounce, elastic, parallax, or content entrance animations. `prefers-reduced-motion: reduce` removes transforms and transitions while preserving state changes.

## Information architecture

The foundation covers five distinct screens. Other legacy administration routes remain reachable from the rail, but are outside this design pass.

### Navigation model

- **Global header, 64 px:** wordmark **Estágio ESS/UFRJ**, descriptor **Coordenação de Estágio e Extensão**, current period chip, help/contact link, and account action. Public visitors see **Entrar**; authenticated staff see their name and **Sair**.
- **Left rail, 248 px on desktop:** grouped navigation with simple 20 px line icons and text labels.
  - **Mural:** Mural de estágios, Histórico.
  - **Cadastros:** Instituições, Alunos, Supervisores.
  - **Coordenação:** Ofertas, Inscrições, Configurações (admin only).
  - **Footer:** contact and discussion-group links.
- Active item: a 3 px cobalt bar, `--accent-soft` background, and `aria-current="page"`.
- On subpages, use a compact breadcrumb: **Mural / Instituição / Inscrição**. Do not repeat the whole rail inside the content.
- Primary mobile navigation is an off-canvas drawer opened from a labelled **Menu** button; focus is trapped while open and returned to the trigger on close.

### Pages

1. **Mural de Estágios** — `/mural/mural-listar.php` — public opportunity board with period context, open-offer count, search/filter controls, and a status-aware table of institutions, vacancies, applicants, benefits, and deadlines.
2. **Detalhe da Oferta** — `/mural/mural-ver_cada.php?muralestagio_id={id}` — single-offer decision page with institution identity, placement conditions, selection details, status, and the application entry point.
3. **Inscrição do Aluno** — `/mural/mural-aluno_inscricao.php?muralestagio_id={id}` — public candidate form that confirms the chosen offer, collects required personal data, validates it, and ends in an unambiguous confirmation state.
4. **Gestão do Mural** — `/gestao/index.php` — authenticated coordination hub summarising the active period, current offers, candidate volume, and shortcuts into offer and applicant management.
5. **Entrar** — `/login.php` — focused institutional sign-in for coordinators and returning users, with a clear way back to the public mural.

The admin hub is a new, thin authenticated route over the existing mural data and controller actions. Until that route exists, its data model maps to the admin state of `mural-listar.php` plus the existing offer and applicant endpoints; do not fork the underlying queries.

### Route and adjacency rules

- **Mural** is the public home and the fallback for unknown or expired links. It links to every offer, to **Entrar**, and to the external institution search.
- **Detalhe da Oferta** links back to **Mural**, forward to **Inscrição** when open, and to coordination contact when closed.
- **Inscrição do Aluno** links back to the selected **Oferta** and **Mural**; success returns to the offer with a confirmation message.
- **Gestão do Mural** links to **Oferta**, applicant lists, and the public **Mural**; it is reached after **Entrar** for admin roles.
- **Entrar** links back to **Mural** and, on success, to **Gestão do Mural** for staff or **Mural** for students.
- Every route uses a meaningful document title: `Mural de Estágios — Estágio ESS/UFRJ`, `{Instituição} — Estágio ESS/UFRJ`, `Inscrição — Estágio ESS/UFRJ`, `Gestão do Mural — Estágio ESS/UFRJ`, `Entrar — Estágio ESS/UFRJ`.

## State and responsiveness plan

### Shell breakpoints

| Width | Behaviour |
| --- | --- |
| 1440 px and above | 248 px rail, 64 px header, fluid content capped at 1600 px; tables use available width. |
| 1280 px (reference) | 248 px rail, 32 px page gutters, full-width data surface. No fixed content column. |
| 1024–1279 px | Rail compresses to 216 px, gutters to 24 px; secondary metadata may wrap, primary columns stay visible. |
| 768–1023 px | Rail becomes an off-canvas drawer; content is full width; filters collapse into a single **Filtrar** control row. |
| Below 768 px | Single-column content, 16–20 px gutters, stacked offer records, full-width actions, 44 px touch targets. |
| Below 480 px | Header wordmark shortens to **Estágio ESS/UFRJ** with the descriptor hidden; period chip moves below the title. |

Do not centre a narrow fixed column inside the shell. The previous `#corpo { width: 750px; }` rule is explicitly rejected; it is the direct cause of the large empty area inside the full-width content frame.

### Global states

- **Guest:** browse Mural and Oferta, start Inscrição, see **Entrar**. Hide emails, administrative actions, period switching, and destructive controls.
- **Student:** show identity in the header; if already applied, replace the apply CTA with a confirmation state and a link to the offer.
- **Coordinator/admin:** show period selector, management shortcuts, applicant links, and edit/print actions. These controls never appear on public pages.
- **Session expired:** preserve the destination, explain the session ended, and offer **Entrar novamente**.
- **Loading:** use a quiet skeleton for async table/filter areas; never blank the whole shell.
- **Error:** inline, specific, recoverable message near the affected module; keep navigation and the fallback path available.

### Mural states

- Default: current period and all open offers; show a visible count plus a compact status legend.
- Filtered: active filter chips with **Limpar filtros**; result count updates; no-results state offers the clear action.
- Offer status: **Inscrições abertas**, **Encerra em breve** (three days or fewer), **Inscrições encerradas**, and **Inscrições na instituição**. Status uses text + icon, never colour alone.
- Agreement status: **Conveniada** / **Não conveniada** as a text badge with a subtle border; remove full-row red/green fills.
- Empty: "Nenhuma oferta neste período" with the next step to check another period (admin) or contact coordination (public).
- Very large screens: the table expands to the available content width; the institution column gets the extra space first.

### Oferta and Inscrição states

- Open offer: primary **Inscrever-se nesta oferta**; secondary **Voltar ao mural**.
- Closing soon: show deadline countdown as text (`Encerra em 2 dias`) next to the status, with the same CTA.
- Closed/direct-to-institution: disable the CTA, explain the alternative, and expose **Falar com a coordenação**.
- Already applied: show **Inscrição registrada**, date and reference, plus **Ver oferta** / **Voltar ao mural**.
- Form: required fields marked before interaction; inline errors after blur or submit; a short error summary at the top; submit remains enabled but reports validation issues; success is a full confirmation panel, not a toast alone.
- Long forms: group into identity, contact, and address fields; keep the chosen offer summary visible in a sticky aside on desktop and a compact summary above the form on mobile.

### Gestão states

- Overview: active period, offers count, candidate count, and offers closing this week.
- Offer table: status, institution, vacancies, applicants, deadline, and actions.
- Applicant drill-down: list with search, offer/group filters, and print/export. Destructive actions require a confirmation dialog with the record name.
- Permission denied: a clear page-level explanation with **Voltar ao mural**; never render disabled admin controls for unauthorised users.

### Accessibility and content rules

- Semantic landmarks, skip link, one `h1` per page, logical heading order, visible `:focus-visible` ring at least 2 px.
- All controls have text labels; icon-only controls have accessible names and tooltips.
- Tables use `<caption>`, `<th scope>`, and numeric alignment; dates are shown as `dd/mm/yyyy` and times as 24-hour.
- Never communicate status by colour alone; maintain at least 4.5:1 for normal text and 3:1 for UI boundaries.
- Keep UI copy in Brazilian Portuguese, concise and action-led. Do not use explanatory marketing copy to compensate for a weak action hierarchy.

## Implementation notes

### Fixing the existing layout

The current shell is `index.php` + `app.css`: a flex row with an iframe set to `flex: 1` and a 250 px sidebar after it. Inside the iframe, `estagio.css` sets `body` to a 10 px margin and `#corpo` to a fixed **750 px** width. On a wide monitor the iframe grows, but its document does not, so the content sits at the left of a large empty band.

The target design removes that failure mode:

- Build one continuous page shell; do not render full HTML documents inside iframes. Navigation and header belong to the shell, not to each offer template.
- Replace the flex content-plus-sidebar construction with CSS Grid: `grid-template-columns: var(--rail) minmax(0, 1fr)`. Put the rail on the leading edge and use `min-width: 0` on the content column.
- Remove `#corpo { width: 750px }`, fixed `width` attributes on layout tables, `align="center"` wrappers, and inline layout styles.
- Use `.page-container { width: 100%; max-width: 1600px; padding-inline: clamp(20px, 3vw, 48px); }`. Prose blocks may cap at 72ch; data surfaces must fill the container.
- As an interim patch if the iframe remains in production for one release, override the inner document with `#corpo { width: 100%; max-width: none; }` and make its page wrapper full width. This is a compatibility patch, not the target architecture.
- Keep one navigation source in the shell; the duplicated inner menu has been removed.

### CSS and component rules

- Define palette, spacing (`4/8/12/16/24/32/48`), radius (`4/8/12`), and type tokens once at `:root`; consume tokens instead of hard-coded colours.
- One outer surface per content region. Use rules and spacing for grouping; do not put cards inside cards or add shadows to every block.
- Buttons: 40 px desktop height, 44 px mobile; primary = solid accent with white text, secondary = white surface with `--line` border and ink text, tertiary = text link with underline on hover.
- Tables: one header row with uppercase 12 px labels, 56 px body rows, tabular numerals, aligned numeric columns, and a quiet hover tint. On screens below 900 px, convert each row into a stacked record inside the same list surface using visible field labels; do not force a six-column horizontal scroll.
- DataTables may remain as a progressive enhancement, but style its wrapper, search, pagination, and length menu to the system tokens. Server-rendered rows must remain readable without JavaScript.
- Use `<details>` only for compact secondary disclosure, not for primary navigation. Draw the chevron with CSS, not an emoji.
- Avoid pure black, gradient backgrounds, glass panels, nested cards, and full-row saturated status colours.

### Screen delivery requirements

- Desktop frames: 1280×800. Also verify 1440×900, 1024×768, 768×1024, and 390×844.
- Each HTML document must include a meaningful `<title>` naming the product and screen.
- The brand **Estágio ESS/UFRJ** must be visible in the first viewport/header on every screen; the task is institutional, not anonymous.
- Each first screen pairs its primary CTA with a visibly different secondary path: Mural (`Ver ofertas` / `Entrar`), Oferta (`Inscrever-se` / `Voltar ao mural`), Inscrição (`Confirmar inscrição` / `Revisar oferta`), Gestão (`Nova oferta` / `Ver mural público`), Entrar (`Entrar` / `Voltar ao mural`).
- Verify keyboard traversal, focus states, form error association, and the reduced-motion fallback before sign-off.

### Content and data guardrails

- Preserve existing route names, controller actions, and Portuguese domain labels where possible; this is a visual and layout refactor, not a data-model rewrite.
- Use real record fields from the mural: instituição, vagas, inscritos, benefícios, encerramento, seleção, convênio, carga horária, requisitos, área, professor, horário, local e forma de seleção.
- Use the existing period vocabulary (`Todos os períodos`, `Turma`, `Período`) and the existing public application flow. Do not invent fees, ratings, testimonials, or fake urgency.
- Public pages must not expose internal emails, phone numbers, deletion controls, or period-switching controls.
- Dates, counts, and empty states should be testable with realistic sample data before visual polish is considered complete.
