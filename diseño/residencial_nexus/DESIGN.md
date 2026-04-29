# Design System Specification: PropTech Premium

## 1. Overview & Creative North Star: "The Architectural Anchor"
This design system moves beyond the standard SaaS "dashboard" look to create a digital environment that mirrors high-end residential architecture. In the Latin American PropTech market, trust is the primary currency. Our North Star—**The Architectural Anchor**—uses intentional weight, tonal depth, and generous white space to create a sense of permanence and reliability.

We reject the "boxed-in" layout of traditional software. Instead, we utilize **Asymmetric Balance** and **Layered Sophistication**. By treating the screen as a series of physical planes rather than a flat grid, we guide the user’s eye through financial data and property management tasks with the ease of walking through a well-lit gallery.

---

## 2. Color & Surface Philosophy
The palette is rooted in a deep, authoritative blue (`primary`), balanced by functional accents that feel organic rather than synthetic.

### The "No-Line" Rule
To achieve a premium "Editorial" feel, **1px solid borders are strictly prohibited for sectioning.** Boundaries must be defined solely through background color shifts or subtle tonal transitions.
*   **Implementation:** Use `surface-container-low` for a sidebar and `surface` for the main content area. The transition of color is the border.

### Surface Hierarchy & Nesting
We use "Tonal Nesting" to create depth. Treat the UI as stacked sheets of fine paper:
*   **Base Layer:** `surface` (#f7f9fc)
*   **Secondary Sectioning:** `surface-container-low` (#f2f4f7)
*   **Elevated Components (Cards):** `surface-container-lowest` (#ffffff)
*   **Interactive Overlays:** `surface-bright` (#f7f9fc) with Glassmorphism.

### The "Glass & Gradient" Rule
For floating elements (modals, dropdowns), use **Glassmorphism**:
*   **Background:** `surface` at 80% opacity.
*   **Effect:** `backdrop-blur: 20px`.
*   **Signature Texture:** Use a subtle linear gradient on Primary CTAs—from `primary` (#00173c) to `primary_container` (#0a2b5e) at a 135° angle—to provide a "soulful" depth that feels high-end.

---

### 3. Typography: The Editorial Scale
We utilize 'Inter' not as a system font, but as a clean, typographic tool. The scale is intentionally dramatic to create a clear information hierarchy.

| Level | Size | Weight | Role |
| :--- | :--- | :--- | :--- |
| **Display-LG** | 3.5rem | 600 | Monthly Revenue & High-Level Metrics |
| **Headline-SM** | 1.5rem | 600 | Property Names & Section Headers |
| **Title-MD** | 1.125rem | 500 | Card Titles & Table Headers |
| **Body-LG** | 1.0rem | 400 | Primary Reading & User Inputs |
| **Label-MD** | 0.75rem | 500 | "Mora" Alerts & Status Micro-copy |

**Typography Strategy:** Use `on-surface-variant` (#44474f) for secondary info to ensure the `on-surface` (#191c1e) titles pop with architectural authority.

---

## 4. Elevation & Depth
Depth in this system is a result of light and shadow, not lines.

*   **The Layering Principle:** Place a `surface-container-lowest` card on a `surface-container-low` background. This creates a natural 12px "lift" without a single drop shadow.
*   **Ambient Shadows:** When a "floating" effect is required (e.g., Modals), use an extra-diffused shadow: `0 20px 25px -5px rgba(10, 43, 94, 0.08)`. Note the tint: we use a low-opacity version of our `primary` blue, never pure black.
*   **The "Ghost Border" Fallback:** For accessibility in form inputs, use a "Ghost Border": `outline-variant` (#c4c6d1) at **20% opacity**. It should be felt, not seen.

---

## 5. Components

### Buttons: High-Contrast Interaction
*   **Primary:** Gradient (Primary to Primary-Container), 8px radius, white text. No shadow on rest; subtle `surface_tint` glow on hover.
*   **Secondary (Success/Payments):** `secondary` (#006b5f) background with `on-secondary` text. Used exclusively for "Confirm Payment" or "Approve."
*   **Tertiary (Ghost):** No background. Text uses `primary`. Used for "Cancel" or "Go Back."

### Form Inputs: The Focused State
*   **Default:** `surface-container-highest` background, no border.
*   **Focus State:** Shift background to `surface-container-lowest` and apply a 2px `primary_fixed` (#d8e2ff) "Ghost Border." The transition should be a 200ms ease-in-out.

### Data Tables: Tonal Rows
*   **Rule:** Forbid divider lines.
*   **Alternative:** Use a 40px vertical spacing scale between entries. On hover, the entire row should shift to `surface-container-low` with a `md` (0.75rem) corner radius.

### Cards: The Metric Vessel
*   **Style:** `surface-container-lowest` background, `xl` (1.5rem) padding. 
*   **Visual Hook:** Use a small 4px accent line of `secondary_fixed` (#99f3e3) only at the top-left to denote "Active" or "Paid" status.

### Modals: The Sanctuary
*   **Radius:** `xl` (1.5rem/24px) for a soft, premium feel.
*   **Overlay:** 40% opacity of `primary` (#00173c) with a `10px` backdrop blur. This isolates the task while keeping the property context visible.

---

## 6. Do’s and Don’ts

### Do
*   **Do** use white space as a structural element. If an interface feels cluttered, increase the spacing rather than adding a divider.
*   **Do** use `secondary` (Emerald) for positive financial actions to build "Payment Confidence."
*   **Do** use `tertiary_container` (#5d0d00) for "Mora" (late payment) alerts to create urgency without inducing panic.

### Don’t
*   **Don’t** use pure black (#000) for text or shadows. It breaks the high-end architectural feel.
*   **Don’t** use "Card-in-Card" layouts. If you need to nest info, use a background color shift, not another bordered box.
*   **Don’t** use high-contrast borders for tables. If the data is hard to read, use subtle zebra-striping with `surface-container-low`.

---

**Director’s Final Note:**
Property management is about the home. This design system should feel like a well-managed building: quiet, sturdy, and effortlessly functional. When in doubt, simplify. Let the typography and the colors do the heavy lifting.