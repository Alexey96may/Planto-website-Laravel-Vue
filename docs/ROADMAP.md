# Roadmap

## Payments & orders

- [ ] Enable Stripe Checkout in production
- [ ] Handle `checkout.session.completed` webhook for async confirmation
- [ ] Discounts system
- [ ] Promo codes
- [ ] Delivery service integration
- [ ] Cron: clear stale carts weekly
- [ ] Automated stock recovery for abandoned `pending` orders (scheduled task)
- [ ] Order invoices in PDF

## Catalog

- [ ] Tags
- [ ] Breadcrumbs
- [ ] Wishlist / recently viewed / compare
- [ ] Multiple file upload (products)
- [ ] Comments on products
- [ ] Slug-based routes (`/plants/monstera-deliciosa` instead of `?id=42`)

## UX / UI

- [ ] Dark mode
- [ ] Shared layout transition
- [ ] Optimistic UI
- [ ] Progress indicator on uploads
- [ ] Infinite scroll (load-more pagination)
- [ ] Magnetic buttons
- [ ] Custom cursor (changes shape on links and buttons)
- [ ] White-space typography pass (kerning, weight-scaled spacing)
- [ ] Reveal / layout animations
- [ ] Lottie animations, vector graphics
- [ ] Blur-up loading

## Accounts & admin

- [ ] Multiple admin roles
- [ ] Admin analytics page
- [ ] Real-time replies (chat with users)
- [ ] Soft deletes: users, products, posts
- [ ] Static pages with SEO stored in DB

## Infrastructure

- [ ] S3 / Cloudinary storage
- [ ] CI: Laravel Pint (PSR-12)
- [ ] SMS + real email in production
- [ ] Component tests (Vue)
- [ ] E2E tests (Playwright)
- [ ] Sentry error tracking
- [ ] Redis + Horizon for async jobs

## Growth

- [ ] Blog with Quill
- [ ] Site translation (i18n)
- [ ] AI integration
