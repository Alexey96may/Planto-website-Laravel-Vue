# 🌿 PlantShop — Fullstack E-commerce Platform

**PlantShop** is a specialized e-commerce solution for botanical enthusiasts. It features a modern interface, real-time inventory management, and a secure payment pipeline. This project demonstrates a deep integration of Laravel 11 and Vue.js 3, with a focus on reliability, database integrity, and handling real-world API challenges.

---

## 🛠 Tech Stack

- **Backend:** PHP 8.2+ | Laravel 11 (Service Layer, Eloquent, Notifications)
- **Frontend:** Vue.js 3 (Composition API) | Inertia.js | Tailwind CSS
- **Database:** SQLite (Atomic Transactions & `lockForUpdate` implementation)
- **Payments:** Stripe API (Checkout Sessions & Webhook handling)
- **Testing:** Mailtrap (SMTP Testing) | Stripe CLI

---

## 🚀 Key Features

### 🛒 Inventory & Stock Integrity

- **Overselling Protection:** Uses database-level locking (`lockForUpdate`) during the checkout process to ensure stock levels are accurate even under high traffic.
- **Atomic Transactions:** Guarantees that order creation and stock decrement happen as a single unit—no "ghost" orders or missing inventory.
- **Persistent Cart:** A robust `CartService` handles all calculations and item persistence on the server side.

### 💳 Payment & Reliability

- **Stripe Checkout:** Secure, PCI-compliant payment processing.
- **Environment-Aware Logic:** Custom handling for local development to bypass network restrictions and SSL/TLS handshake timeouts.
- **Webhook Ready:** Architecture prepared for asynchronous payment confirmation and automated order fulfillment.

### 📧 Notification System

- **Automated Receipts:** Instant email notifications triggered by order status changes.
- **Development SMTP:** Configured to work seamlessly with Mailtrap, including SSL bypass for local OpenSSL environments.

---

## 📦 Installation & Setup

1.  **Clone the repository:**

    ```bash
    git clone [https://github.com/your-username/plant-shop.git](https://github.com/your-username/plant-shop.git)
    cd plant-shop
    ```

2.  **Install dependencies:**

    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration:**

    ```bash
    cp .env.example .env
    # Update your DB_DATABASE, STRIPE_SECRET, and MAIL credentials
    ```

4.  **Database Migration:**

    ```bash
    php artisan migrate --seed
    ```

5.  **Run the Application:**
    ```bash
    npm run dev
    php artisan serve
    ```

---

## 📈 Roadmap

- [ ] **Automated Stock Recovery:** Scheduled Task to restock items from abandoned "pending" orders.
- [ ] **Advanced Filtering:** Filter plants by light requirements, pet-friendliness, and difficulty level.
- [ ] **User Profiles:** Dashboard for users to track their order history and plant "wishlist".

---

## 👤 Author

**Aleksey** — Fullstack Developer

- **Specialization:** Laravel, Vue.js, Node.js
