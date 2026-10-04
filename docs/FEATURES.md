# Features

Full list of implemented functionality. See [ROADMAP.md](ROADMAP.md) for planned work.

## Backend

- Controllers, Services, Observers
- Laravel Events/Listeners for emails and in-app notifications
- Mail: order receipts, newsletter subscription
- Telegram bot: new-order notification to admin chat
- Notifications on order status change (triggered from admin)
- Guest checkout + automatic account registration after order
- Review system with moderation
- Cart stored in DB, tied to the user on login
- Product photo upload via admin (Spatie Laravel Media Library)
- Pagination (DB-side) for admin and catalog
- Order status flow: `pending` → `processing` → paid (when Stripe enabled)
- Stripe Checkout integration (implemented, disabled by default)
- SEO helpers per controller: meta tags, Open Graph, Twitter, canonical

## Database

- PostgreSQL (migrated from SQLite)
- Plant types table
- Settings table (footer, hero, contacts)
- Banners table (image, heading, link, description — "Our Best o2" section)
- `is_trending` flag on products (admin-controlled)
- Top Selling based on purchases / rating
- Cart persistence in DB, sync on login

## Frontend

- Swipers (carousels)
- Filter watcher automation in Shop
- Debounced product search
- Toast with Undo for destructive actions
- Photo upload: format and size checks, `accept="image/*"`, drag & drop, preview, client-side compression via `browser-image-compression`
- Animated cart button when a product is already in the cart
- Skeletons and loaders
- Mapbox map on Contacts page
- Season-aware canvas animation (petals, leaves, snowflakes depending on user's season)
- Smooth page transitions
- Section fade-in on Home, Cart, Terms
- Mobile focus and hover states
- Drag & drop (admin)
- Snap scroll, smooth scroll
- Hover + parallax
- Default avatar with the user's initial
- Next-gen image formats
- Floating labels, real-time validation
- Ambient sound landscape (Howler) + toggle to disable animations and audio
- Vibrations on admin navigation
- Photo preview component with `handleImageError` handling

## SEO

- Inertia SSR
- Meta tags per controller, including Open Graph and Twitter
- Sitemap
- robots.txt
- Canonical URLs
- Different logos depending on page type
- Full ARIA coverage: `aria-label`, `aria-busy`, `aria-hidden`, `aria-selected`, `aria-current`, `aria-disabled`, `aria-checked`, `aria-invalid`, `aria-describedby`, `aria-haspopup`, `aria-expanded`, `aria-live`, `aria-labelledby`, `role` attributes

## Tests

- Unit tests
- Feature tests (API-level: cart logic, DB/session changes)

## Known limitations

- Scroll jump when a modal closes on desktop
- Category animations in Shop
- Stripes on the home page caused by fade-in

## Libraries

**Vue**

- Ziggy, Lucide, Heroicons
- Howler, GSAP, Swiper
- Lodash
- Quill

**Laravel**

- Spatie Laravel Media Library
- Stripe
