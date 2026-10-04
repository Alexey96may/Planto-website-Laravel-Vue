# 🌿 PlantShop — E-commerce Shop for Plants

Pet project of an online houseplant store built with Laravel 11 + Vue 3 + Inertia.js.
Demonstrates database transactions, overselling protection, Stripe Checkout integration, and webhook handling.

---

## 📸 Demo

![Demo](docs/screenshots/Planto.gif)

| Home                                      | Shop                                      | Cart                                      |
| ----------------------------------------- | ----------------------------------------- | ----------------------------------------- |
| ![home](docs/screenshots/Planto_main.jpg) | ![shop](docs/screenshots/Planto_shop.jpg) | ![cart](docs/screenshots/Planto_cart.jpg) |

| Profile                                   | Admin                                       | Contact                                         |
| ----------------------------------------- | ------------------------------------------- | ----------------------------------------------- |
| ![user](docs/screenshots/Planto_user.jpg) | ![admin](docs/screenshots/Planto_admin.jpg) | ![contact](docs/screenshots/Planto_contact.jpg) |

> No live demo yet. The project runs locally in a few minutes — see [Installation](#-installation).

---

## 🛠 Tech Stack

**Backend**

- PHP 8.2+, Laravel 11
- Service Layer, Eloquent, Notifications
- PostgreSQL 14+

**Frontend**

- Vue 3 (Composition API)
- Inertia.js
- Tailwind CSS
- Vite

**Payments**

- Stripe Checkout Sessions + Webhooks

**Local dev tools**

- Mailtrap (catches outgoing emails in dev)
- Stripe CLI (forwards webhooks locally)

---

## ✨ Features

### 🛒 Inventory & stock

- **Overselling protection** — during checkout, the product row is locked with `lockForUpdate` so two concurrent requests can't sell the same unit.
- **Atomic transactions** — order creation and stock decrement happen inside a single DB transaction.
- **Server-side cart** — `CartService` stores items and recalculates totals on the server, not on the client.

### 💳 Payments

- **Stripe Checkout** — payment goes through Stripe's hosted checkout page.
- **Webhooks** — `checkout.session.completed` handler confirms payment and moves the order to paid status.
- **Dev mode** — separate configuration for local development without public webhooks.

### 📧 Notifications

- Order status change triggers a receipt email to the customer (Laravel Notifications).
- Emails are caught with Mailtrap locally.

---

## 🚀 Installation

### Requirements

- PHP 8.2+
- Composer 2
- Node.js 18+ and npm
- PostgreSQL 14+
- Stripe account (test keys) — needed only to try the payment flow

### Steps

```bash
# 1. Clone
git clone https://github.com/your-username/plant-shop.git
cd plant-shop

# 2. Dependencies
composer install
npm install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Create the database
createdb plant_shop
# or: psql -U postgres -c "CREATE DATABASE plant_shop;"

# 5. In .env set:
#    DB_CONNECTION=pgsql
#    DB_HOST=127.0.0.1
#    DB_PORT=5432
#    DB_DATABASE=plant_shop
#    DB_USERNAME=postgres
#    DB_PASSWORD=your_password
#
#    STRIPE_KEY / STRIPE_SECRET — test keys from dashboard.stripe.com
#    STRIPE_WEBHOOK_SECRET — you'll get this on step 8
#    MAIL_* — Mailtrap, or set MAIL_MAILER=log (emails go to storage/logs)

# 6. Migrate and seed
php artisan migrate --seed

# 7. Run backend and frontend in two terminals
php artisan serve       # terminal 1
npm run dev             # terminal 2

# 8. (Optional) Receive Stripe webhooks locally
stripe listen --forward-to localhost:8000/stripe/webhook
# Copy whsec_... into STRIPE_WEBHOOK_SECRET
```
