\# FireVault — Design Guideline

\*\*Free Fire ID Marketplace & Sales Management System\*\* A small, single-purpose storefront — the design's job is to make three or four listings on a homepage feel like a curated vault, not a template.

\---

\#\# 1\. Design Premise

FireVault is selling trust as much as it's selling accounts. A buyer has to believe a screenshot, a level number, and a stranger on WhatsApp before they'll send money. So the whole visual language should read as \*\*"inventory you can verify,"\*\* not "generic marketplace template." The reference point is Pinterest for one specific reason: Pinterest treats every image as the primary unit of information and lets pieces sit at their natural size in a flowing grid, rather than forcing everything into identical boxes. Free Fire screenshots are all different aspect ratios (portrait phone captures, landscape stat screens) — a masonry grid respects that instead of cropping it away.

Everything below is written for this exact product: a dark, premium, mobile-first, image-led catalog of one-of-a-kind digital items, sold one at a time, by a small team.

\---

\#\# 2\. Design Tokens

\#\#\# Color

A vault at night, lit by the warm glow of something valuable inside it — that's the palette logic. Two accents, used for different jobs, not one bright neon doing everything.

| Token | Hex | Role |  
| \--- | \--- | \--- |  
| \`bg-base\` | \`\#0F1115\` | Page background — graphite, not pure black; has a faint cool undertone |  
| \`bg-surface\` | \`\#181B21\` | Cards, panels, modals — one step up from base |  
| \`bg-surface-raised\` | \`\#20242C\` | Hover/active surface state, nested panels |  
| \`border-hairline\` | \`\#2B2F38\` | Dividers, card outlines, table rules |  
| \`text-primary\` | \`\#EDE9DE\` | Body/headline text — warm off-white, not stark \`\#FFF\` |  
| \`text-muted\` | \`\#8E93A0\` | Secondary text, metadata, timestamps |  
| \`accent-gold\` | \`\#E3A339\` | Primary accent — CTAs, price, featured marker, focus ring |  
| \`accent-teal\` | \`\#3FA79B\` | Secondary accent — "Available" status, links, moderator/staff UI |  
| \`status-sold\` | \`\#6B6F7A\` | Sold overlay/stamp — deliberately desaturated, not red (red is reserved for real errors) |  
| \`status-danger\` | \`\#D9534F\` | Genuine errors/destructive actions only |

\*\*Why not the obvious choices:\*\* no cream-and-terracotta (too soft/editorial for a gaming product), no single acid-neon-on-black (too generic-tech, and Free Fire's own brand already owns bright orange/red — competing with it looks derivative rather than premium). Gold reads as "rare loot" without literally using game-UI purple/orange rarity colors; teal keeps "available/trustworthy" distinct from gold so price and status never fight for attention on the same card.

\#\#\# Typography

Two families, clearly distinct roles — no third face, no decorative script.

| Role | Typeface | Notes |  
| \--- | \--- | \--- |  
| Display / headlines | \*\*Sora\*\* (SemiBold/Bold) | Geometric, slightly technical without being a cliché "coding" monospace — carries the gaming/product personality |  
| Body / UI / data | \*\*Inter\*\* (Regular/Medium) | Built for small, dense, numeric UI — levels, prices, counts — stays legible at 13–14px on cards |

\- Type scale (desktop): 48/36 display, 28/22 section headings, 16 body, 14 UI/meta, 13 captions. Mobile display drops to 32/26.  
\- Line length: body copy (How It Works, Safety, T\&C) capped around 68–72 characters.  
\- No tracked-out all-caps labels anywhere (status chips, section labels use sentence case, e.g. "Featured today," not "FEATURED").  
\- Headlines are set in full — no single word picked out in a different color or weight. If something needs emphasis, it earns its own line or its own component (a badge), not a font trick mid-sentence.

\#\#\# Spacing & Shape

\- Base unit: 4px. Card padding 20px, section padding 64–96px desktop / 32–40px mobile.  
\- \*\*Two corner treatments, used consistently, not one radius everywhere:\*\* listing cards and images use a soft \`12px\` radius (photographic content wants to feel handled gently); data chips, price tags, and buttons use a tighter \`6px\` radius (these are UI, not photos — they should read as precise, not decorative). This distinction is itself a piece of visual grammar: soft \= image, tight \= data.  
\- Shadows are used sparingly and only on truly elevated elements (open modal, dropdown) — cards at rest sit flat against \`bg-base\`, separated by the hairline border and a slightly lighter surface tone, not a drop shadow. Avoid the identical soft-grey-shadow-under-every-card look.

\---

\#\# 3\. Layout Concept

\#\#\# Homepage — asymmetric, image-led hero (not the generic centered-headline-plus-gradient default)

\`\`\`  
┌───────────────────────────────────────────────────┐  
│  FireVault      Browse   How It Works   Safety   ☰ │  
├───────────────────────────────────────────────────┤  
│                             │                       │  
│   \[ full-bleed screenshot   │   The account is      │  
│     of today's best         │   real. The seller     │  
│     featured listing,       │   is verified. The      │  
│     slightly cropped \]      │   handoff is on         │  
│                             │   WhatsApp.             │  
│                             │                       │  
│                             │   \[ Search bar \]        │  
│                             │   Browse all IDs →       │  
├───────────────────────────────────────────────────┤  
│  Featured today                                     │  
│  ┌────┐ ┌──────┐ ┌────┐ ┌──────┐   ← masonry row,   │  
│  │    │ │      │ │    │ │      │     varied heights   │  
│  └────┘ └──────┘ └────┘ └──────┘                    │  
└───────────────────────────────────────────────────┘  
\`\`\`

The hero is a real screenshot from a real listing (rotating), not an illustration or gradient blob — it's the most characteristic thing in this product's world. Copy sits right-aligned next to it, left-aligned text block, not centered.

\#\#\# Browse page — Pinterest-style masonry

\`\`\`  
┌────┐ ┌──────┐ ┌────┐ ┌────┐  
│    │ │      │ │    │ │    │  
│    │ └──────┘ │    │ └────┘  
└────┘ ┌────┐   └────┘ ┌──────┐  
┌──────┐│    │ ┌──────┐│      │  
│      │└────┘ │      │└──────┘  
└──────┘        └──────┘  
\`\`\`

\- Columns: 4 (desktop, ≥1280px) → 3 (tablet, ≥900px) → 2 (small tablet/large phone) → 1 (phone, \\\<420px).  
\- Cards keep their screenshot's natural aspect ratio; the grid re-flows around whatever height that produces, exactly like Pinterest — no forced-uniform crop.  
\- Filter/sort bar is a single slim sticky row above the grid (price range, level range, featured toggle, sort) — collapses into one "Filters" button \+ slide-up sheet on mobile, not a stacked sidebar.

\#\#\# Listing detail — full-bleed gallery, not a boxed product page

\`\`\`  
┌───────────────────────────────────────────────────┐  
│  ← Back to browse                                  │  
├───────────────────────┬───────────────────────────┤  
│                       │  Heroic Rank — Elite Pass   │  
│   \[ large image,      │  Collection                 │  
│     swipeable         │                             │  
│     gallery,          │  ৳6,500        Available ● │  
│     thumbnails        │                             │  
│     below \]           │  Level 68 · 2 yrs old        │  
│                       │  12 characters · 34 skins    │  
│                       │                             │  
│                       │  \[  Contact on WhatsApp  \]   │  
│                       │                             │  
│                       │  Description...              │  
└───────────────────────┴───────────────────────────┘  
\`\`\`

Gallery takes \\\~60% of the viewport on desktop; stacks full-width above the details on mobile, with details sheet scrolling underneath. The WhatsApp button is the single gold-filled element on the page — everything else on this screen stays quiet so that button is unmistakably the next action.

\---

\#\# 4\. Core Components

| Component | Direction |  
| \--- | \--- |  
| \*\*Listing card\*\* | Screenshot fills the card top-to-edge at its natural ratio; a bottom gradient scrim (dark, \\\~40% height) sits under the price/level so text stays legible over any image without a solid text bar. Featured items get a small gold corner tag reading "Featured," not a flame emoji or glowing border. |  
| \*\*Status indicator\*\* | A small dot \+ word, not a loud badge: teal dot \= Available, grey dot \= Sold (card also gets a subtle desaturation), amber dot reserved for Featured only when also shown alongside status. Never stack more than one loud visual treatment on a single card. |  
| \*\*Sold treatment\*\* | Card desaturates \\\~40% and gets a small diagonal "Sold" ribbon in the corner — evokes a claimed inventory slot, not a big red stamp across the whole image (the image is still evidence of what the account looked like; don't obscure it). |  
| \*\*Price\*\* | Set in Inter Medium, tabular numbers, always with the ৳ symbol; listed price never shown with a false strikethrough discount — this isn't retail, there's no "original price." |  
| \*\*WhatsApp CTA\*\* | Filled \`accent-gold\` button, WhatsApp glyph \+ "Contact on WhatsApp" (never just "Contact" or "Buy Now" — the label should say exactly what happens, and there's no purchase happening on-site). One per listing detail view; it's the single loud button on the page. |  
| \*\*Search bar\*\* | Hairline border, \`bg-surface\` fill, no icon-only affordance — always paired with the word "Search." |  
| \*\*Filter chips\*\* | Tight \`6px\` radius, hairline border when inactive, \`accent-teal\` fill when active — never gold, so filters never compete visually with the WhatsApp CTA or price. |  
| \*\*Admin/Moderator dashboard cards\*\* | Same token system, but flatter and denser — this audience needs to scan numbers fast, not browse imagery. Stat cards use \`bg-surface\`, large Inter numerals, small muted labels below (not above) the number. |  
| \*\*Empty states\*\* | Plain-spoken and actionable: "No listings yet. Add your first Game ID to get started." — never a mascot illustration or forced joke; this is a staff tool for part of its surface. |

\---

\#\# 5\. Imagery Direction

\- Screenshots are the product photography — treat them with respect: no heavy filters, no added neon glow, no watermark clutter beyond a small, unobtrusive FireVault mark in a corner if needed for anti-scraping.  
\- Cover image selection matters: encourage moderators (in the upload UI copy) to pick the screenshot that best shows overall account value (inventory/skin overview) as the cover, save close-up detail shots for the gallery.  
\- No stock photography, no illustrated characters, no generic "gamer with headset" imagery anywhere on the site — every image on FireVault should be a real screenshot of a real account.

\---

\#\# 6\. Motion

One deliberate moment, not motion on every element:

\- \*\*Homepage load:\*\* the hero screenshot and its copy settle into place once, together, on first load — a single orchestrated reveal, not staggered fade-ins on every subsequent section.  
\- \*\*Card hover (desktop only):\*\* image scales to 1.03 and the card lifts with a very slight shadow — this is the one hover treatment used everywhere, applied consistently rather than invented per-component.  
\- \*\*Gallery swipe/transition:\*\* direct, physical — follows the finger/cursor, no bounce-heavy easing.  
\- \*\*Everything else is instant.\*\* No slide-up-on-scroll for every section, no staggered list-item fade-ins on the browse grid (with potentially hundreds of cards, that would also just feel slow). Respect \`prefers-reduced-motion\` by disabling the hero reveal and hover scale, keeping only functional transitions (opening a modal, a filter sheet sliding up).

\---

\#\# 7\. Accessibility & Quality Floor

\- Text-on-\`bg-base\`/\`bg-surface\` combinations meet at least WCAG AA contrast (\`text-primary\` and \`text-muted\` are calibrated against \`\#0F1115\` and \`\#181B21\` specifically — re-check if either token changes).  
\- Visible keyboard focus ring in \`accent-gold\`, 2px, offset — never removed, never \`outline: none\` without a replacement.  
\- All interactive elements have a minimum 44×44px touch target on mobile, including gallery thumbnails and filter chips.  
\- Status is never color-only: the Available/Sold/Featured dots are always paired with a text label, so the distinction survives for colorblind users and in any monochrome context (e.g., a printed or low-color screenshot).  
\- Every listing image has descriptive alt text generated from the listing title and key stat (e.g., "Heroic Rank account, level 68, Elite Pass collection — screenshot 2 of 6").

\---

\#\# 8\. Voice & Content

\- \*\*Buttons say exactly what happens:\*\* "Contact on WhatsApp," "Mark as sold," "Add game ID," "Save changes" — never "Submit," "Proceed," or "Buy Now." A button's label should still make sense to someone reading only that word.  
\- \*\*Status and errors are stated plainly, without apology:\*\* "This listing is no longer available" (not "Oops\! This one's gone 🙈"), "WhatsApp number missing — add one to enable contact" (not a vague "Something went wrong").  
\- \*\*Staff-facing copy is written for people doing a job, quickly:\*\* dashboard labels are short nouns ("Revenue this month," "Open todos"), not marketing phrases.  
\- \*\*No manufactured urgency:\*\* avoid "Only 1 left\!" or countdown-style pressure language — there genuinely is only one of each account, and the product's credibility rests on being straightforward rather than salesy.

\---

\#\# 9\. What This Design Deliberately Avoids

So the intent stays legible as the product evolves:

\- No warm cream background with a terracotta accent (reads as a generic AI-default aesthetic, not a gaming marketplace).  
\- No single bright neon accent on near-black (the two-accent gold/teal system does more useful work and avoids the "generic dark SaaS" tell).  
\- No identical rounded cards with the same soft grey shadow under everything — shape and elevation are used to distinguish image content from UI content.  
\- No tracked-out all-caps eyebrow labels, no middle-dot-joined meta strings, no arrow (→) tacked onto every link.  
\- No numbered-step markers unless content is genuinely sequential (How It Works can use them; a grid of listings never should).  
\- No stock gamer imagery, no mascot, no confetti/celebration animation on sale — the tone throughout stays confident and quiet, letting real screenshots and real numbers carry the credibility.

