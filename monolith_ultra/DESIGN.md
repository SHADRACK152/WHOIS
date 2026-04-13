# Design System Specification: The Monochromatic Authority

## 1. Overview & Creative North Star
The Creative North Star for this design system is **"The Digital Architect."** 

In the chaotic landscape of domain acquisition and AI-powered search, this system acts as a steady, authoritative hand. It moves beyond "minimalism" into "intentionalism." We do not just remove elements; we curate them. The visual identity is built on the tension between high-contrast typography and soft, tonal layering. By embracing a strictly monochromatic palette, we shift the user's focus from "interface" to "information." The goal is to make the act of searching for a digital identity feel like walking through a high-end, quiet gallery—refined, spacious, and premium.

---

## 2. Colors: The Tonal Spectrum
This system rejects the "flat" look of early SaaS. We use a sophisticated hierarchy of greys to create a sense of environment and physical space.

### The Palette
*   **Primary (Brand Power):** `#000000` (The anchor of the identity).
*   **Surface (Foundational):** `#F9F9F9` (The canvas; softer than pure white to reduce eye strain).
*   **Accent/Neutral:** `#737373` (For secondary information and metadata).
*   **Outline/Variant:** `#E5E5E5` (The ghost-layer).

### The "No-Line" Rule
To achieve a high-end editorial feel, designers are prohibited from using 1px solid borders for sectioning large layouts. Boundaries must be defined through **Background Color Shifts**. 
*   *Example:* A `surface-container-low` (#F3F3F3) section should sit flush against a `surface` (#F9F9F9) background. The change in tone is the divider.

### Surface Hierarchy & Nesting
Treat the UI as stacked sheets of fine vellum.
1.  **Level 0 (Base):** `surface` (#F9F9F9)
2.  **Level 1 (Sectioning):** `surface-container-low` (#F3F3F3)
3.  **Level 2 (Cards/Interaction):** `surface-container-lowest` (#FFFFFF)

### The "Glass & Gradient" Rule
While we are monochromatic, we are not "flat." Use **Glassmorphism** for floating elements (like Search Bars or Navigation). Apply a `surface-container-lowest` (#FFFFFF) at 80% opacity with a `20px` backdrop blur. This ensures the AI’s dynamic search results feel integrated into the page architecture rather than sitting on top of it.

---

## 3. Typography: Editorial Sophistication
We use a dual-font approach to create an "Editorial SaaS" vibe.

*   **Display & Headlines (Manrope):** We use Manrope for its geometric precision. It feels "engineered" yet approachable. Use `headline-lg` (2rem) with tight letter-spacing (-0.02em) for an authoritative, "Apple-esque" impact.
*   **Body & Labels (Inter):** Inter is our workhorse. Its high x-height ensures maximum readability for complex domain strings and technical AI data.

**Hierarchy as Identity:**
*   **AI Insights:** Use `label-md` in all-caps with 0.1em letter spacing to denote AI-generated suggestions.
*   **Price Points:** Use `title-lg` (Inter) for domain pricing to maintain a utilitarian, professional clarity.

---

## 4. Elevation & Depth
Depth in this system is achieved through **Tonal Layering** and **Ambient Shadows**, never through heavy strokes.

*   **The Layering Principle:** Place a `#FFFFFF` card on a `#F9F9F9` background. This "soft lift" is the primary method of separation.
*   **Ambient Shadows:** For "Active" states or floating modals, use a shadow with a high blur (30px - 60px) and ultra-low opacity (4%-8%). The color must be `#000000`—creating a natural, atmospheric lift rather than a harsh drop-shadow.
*   **The "Ghost Border" Fallback:** If a border is required for accessibility in input fields, use the `outline-variant` (#C6C6C6) at 40% opacity. It should feel like a suggestion of a line, not a container.

---

## 5. Components

### Buttons
*   **Primary:** Solid `primary` (#000000) with `on-primary` (#E2E2E2) text. 
    *   *Interaction:* Hover shifts to `primary-container` (#3B3B3B). 
    *   *Shape:* `xl` (1.5rem) or `full` (9999px) for search actions.
*   **Secondary:** Ghost style. No background, `outline-variant` border at 20% opacity. 

### Input Fields (The Search Experience)
The domain search bar is the hero. 
*   **Style:** `surface-container-lowest` (#FFFFFF) with a `1px` border of `outline-variant` (#C6C6C6).
*   **Focus State:** The border thickens slightly to `1.5px` and turns `primary` (#000000). Use a subtle `4%` ambient shadow to make the field "pop" during the search.

### Domain Cards & Lists
*   **The Rule:** NO divider lines between domain results.
*   **Separation:** Use vertical white space (from the `lg` spacing scale) and subtle hover transitions where the card background shifts from `surface` to `surface-container-high` (#E8E8E8).

### Progress Indicators (AI Search)
*   Instead of a spinning wheel, use a subtle "shimmer" gradient moving across a `surface-variant` (#E2E2E2) bar. This mimics the "scanning" nature of an AI search.

---

## 6. Do’s and Don’ts

### Do:
*   **Do** use extreme whitespace. If you think there is enough margin, add 16px more.
*   **Do** use intentional asymmetry. A left-aligned headline with a right-aligned search result creates a modern, editorial rhythm.
*   **Do** use "Optical Centering." AI domain results should feel balanced on the page, even if the grid is non-traditional.

### Don't:
*   **Don't** use pure #000000 for body text. Use `on-surface-variant` (#474747) to keep the reading experience "soft." Reserve pure black for headlines and primary buttons.
*   **Don't** use standard "Blue" for links. Use a `primary` (#000000) underline that appears on hover.
*   **Don't** use sharp corners. Every element must use at least the `DEFAULT` (0.5rem) corner radius to maintain the "High-End SaaS" approachability.