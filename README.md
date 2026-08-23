# LR Shop

E-commerce platform built with Laravel and React.

## Stack

- **Backend:** Laravel 12, MySQL, Sanctum (Bearer tokens)
- **Frontend:** React, Vite (planned)

## Structure

```text
LR--Shop/
├── backend/     # REST API
└── frontend/    # Storefront (planned)
```

## Backend status

Implemented:

- Categories API (read-only)
- Products API (read-only)
- Guest cart API (`X-Cart-Token`)
- Auth API (register, email verify, resend verify, login, logout, forgot/reset/change password); public auth routes rate-limited (`login`/`register`/`reset-password` 5/min; `forgot-password`/`verification-notification` 3/min)
- User carts (`user_id`) and guest cart merge on login/register
- Orders API (checkout, list, show — auth required; shipping from profile)
- Delivery methods (public list + admin CRUD); checkout requires `delivery_method_id` with price/`free_over` snapshot
- Payment methods (public list + admin CRUD); checkout requires `payment_method_id` with name snapshot; methods have a stable `key` (`cash_on_delivery`, `stripe`)
- Order `payment_status` (`pending` / `paid` / `failed` / `refunded`); checkout starts as `pending`; admin can update via order PATCH
- Stripe test payments: PaymentIntent on checkout (`client_secret`); webhook marks `paid` (+ email) or `failed`; admin full refund via Stripe API
- Order currency snapshot from `shop.currency` settings at checkout
- Taxes (admin CRUD); products optional `tax_id`; checkout snapshots inclusive VAT on items / order (`tax_amount`) without changing `total`
- Product `sale_price` (optional); cart/checkout use effective price; order items snapshot `original_price` for email strikethrough
- Order confirmation email (COD on checkout; Stripe after successful payment — Mailpit locally)
- Admin new-order email to `shop.email` on every successful checkout (COD and Stripe)
- Invoice PDF (`barryvdh/laravel-dompdf`) attached to confirmation email when `payment_status` becomes `paid` (Stripe webhook or admin PATCH)
- Order status change email to customer when admin updates fulfillment `status` (Mailpit locally)
- CORS configured via `FRONTEND_URL` env variable (default `http://localhost:5173`)
- API locale: `en` / `sr` via `Accept-Language`, `?lang=`, or `shop.locale` (API messages, validation, emails)
- Profile API (GET/PATCH/DELETE — hard delete; admins blocked)
- E.164 phone validation on profile and checkout
- Clear cart (`DELETE /cart`)
- User roles (`customer` / `admin`) and admin middleware
- User `is_active` flag; inactive users cannot log in
- Admin uploads, category CRUD, product CRUD, order status / payment status, user management, shop settings, delivery methods, payment methods, and taxes
- Product stock: cart cannot exceed stock; checkout decrements; cancel/fail/refund restores
- Category, product, and cart seed data
- API Resources for JSON responses
- Route model binding by slug
- Product filtering, search, sort, pagination
- Clean JSON 404 responses for API routes

Planned:

- React storefront

## API

Base URL: `http://localhost:8000/api/v1`

### Locale

API `message` strings, validation errors, and transactional emails use Laravel lang files (`lang/{en,sr}/api.php`, `validation.php`, `mail.php`).

| How | Example |
| --- | ------- |
| Header | `Accept-Language: sr` |
| Query | `?lang=sr` |
| Shop default | `shop.locale` setting (`en` / `sr`) — used when header/query missing; also for Stripe webhook emails |

Supported: `en`, `sr`. Product/category content is not translated.

### Categories

| Method | Endpoint             | Description         |
| ------ | -------------------- | ------------------- |
| GET    | `/categories`        | List all categories |
| GET    | `/categories/{slug}` | Single category     |

**Category fields:** `id`, `name`, `slug`, `description`, `image`, `products_count`

### Products

| Method | Endpoint           | Description                      |
| ------ | ------------------ | -------------------------------- |
| GET    | `/products`        | List active products (paginated) |
| GET    | `/products/{slug}` | Single product                   |

**Product fields:** `id`, `name`, `slug`, `description`, `price`, `sale_price`, `effective_price`, `on_sale`, `stock`, `image`, `is_active`, `created_at`, `updated_at`, `category`, `tax_id`, `tax` (`id`, `name`, `rate` when loaded)

`sale_price` is optional. When set and lower than `price`, `on_sale` is true and `effective_price` is `sale_price`; otherwise `effective_price` equals `price`. Cart and checkout charge `effective_price`.

**Query parameters (index only):**

| Param      | Example   | Description                                                 |
| ---------- | --------- | ----------------------------------------------------------- |
| `category` | `laptops` | Filter by category slug                                     |
| `search`   | `phone`   | Search in name and description                              |
| `sort`     | `price`   | Sort field: `name`, `price`, `created_at` (default: `name`) |
| `order`    | `desc`    | Sort direction: `asc` or `desc` (default: `asc`)            |
| `per_page` | `20`      | Items per page, 1–50 (default: `10`)                        |

**Examples:**

```text
GET /api/v1/products
GET /api/v1/products?category=laptops
GET /api/v1/products?search=illo&sort=price&order=desc&per_page=20
GET /api/v1/products/macbook-pro
GET /api/v1/categories/phones
```

### Auth

Sanctum **Bearer** tokens. Login returns the token in `data.token`. Send it on protected routes:

```text
Authorization: Bearer {token}
Accept: application/json
```

| Method | Endpoint                    | Auth | Description                                              |
| ------ | --------------------------- | ---- | -------------------------------------------------------- |
| POST   | `/register`                              | no   | Create account, send verification email (**no token**)            |
| GET    | `/email/verify/{id}/{hash}`              | no   | Confirm email via signed link from mail (no token)                |
| POST   | `/email/verification-notification`       | no   | Resend verification link (`{ "email": "..." }`)                   |
| POST   | `/login`                                 | no   | Return token (unverified or inactive → **403**)                   |
| POST   | `/logout`                   | yes  | Revoke current token                                     |
| POST   | `/forgot-password`          | no   | Send reset link (Mailpit locally)                        |
| POST   | `/reset-password`           | no   | Set new password using email token                       |
| POST   | `/change-password`          | yes  | Change password while logged in                          |

**Rate limits (public auth):** `login`, `register`, `reset-password` → 5 requests/minute; `forgot-password`, `email/verification-notification` → 3/minute. Over limit → **429**. Protected customer/admin routes use `throttle:api` (60/minute).

**Email verification:** `POST /register` creates the user with `email_verified_at = null` and sends a signed link (Mailpit locally). Open the full URL from the mail (browser or GET in Postman). Success: `"Email verified."` — still **no** token. Then `POST /login`. Unverified login → **403** `Please verify your email first.` Inactive account (`is_active = false`) → **403** `Your account is not active. Please contact support.` Invalid credentials still **401**. The verify URL includes `expires` and `signature`; do not build it by hand.

### Profile

Logged-in users only (`auth:sanctum`).

| Method | Endpoint   | Auth | Description                                      |
| ------ | ---------- | ---- | ------------------------------------------------ |
| GET    | `/profile` | yes  | Current user (includes role and shipping fields) |
| PATCH  | `/profile` | yes  | Partial update (`sometimes` fields)              |
| DELETE | `/profile` | yes  | Hard delete account (`204`); admin → `403`       |

**DELETE `/profile`:** removes the user, Sanctum tokens, cart, and orders (DB cascade). Admin accounts cannot be deleted this way.

**Profile fields:** `id`, `name`, `email`, `role`, `phone`, `shipping_address`, `city`, `state`, `zip`, `country`, timestamps. `token` only on login.

**Phone:** optional. If sent, E.164 (`+` and 8–15 digits, e.g. `+381641234567`). Spaces and dashes are stripped. Invalid format → **422**. Register does not collect phone.

**PATCH `/profile` body** (send only what you change):

```json
{
  "name": "Ana",
  "phone": "+381641234567",
  "shipping_address": "Bulevar 1",
  "city": "Beograd",
  "state": "Srbija",
  "zip": "11000",
  "country": "RS"
}
```

**Register body:**

```json
{
  "name": "Ana",
  "email": "ana@example.com",
  "password": "Secret1!",
  "password_confirmation": "Secret1!"
}
```

Password must be at least 8 characters, with mixed case, a number, and a symbol.

**Login body:**

```json
{
  "email": "ana@example.com",
  "password": "Secret1!"
}
```

**Forgot password body:**

```json
{
  "email": "ana@example.com"
}
```

Always returns the same message (does not reveal if the email exists). Local mail: Mailpit at `http://127.0.0.1:8025` (`MAIL_MAILER=smtp`, `MAIL_HOST=127.0.0.1`, `MAIL_PORT=1025`). Reset link uses `FRONTEND_URL` (default `http://localhost:5173`).

**Reset password body** (token and email from the mail link):

```json
{
  "email": "ana@example.com",
  "token": "<from mail>",
  "password": "NovaPass1!",
  "password_confirmation": "NovaPass1!"
}
```

**Change password body:**

```json
{
  "current_password": "Secret1!",
  "new_password": "NovaPass1!",
  "new_password_confirmation": "NovaPass1!"
}
```

### Cart

Cart routes are public. Who owns the cart depends on headers:

| Client    | How cart is resolved                                       |
| --------- | ---------------------------------------------------------- |
| Guest     | `X-Cart-Token` header (UUID)                               |
| Logged in | `Authorization: Bearer {token}` → cart with that `user_id` |

The cart token is returned in the **response header** `X-Cart-Token` (not in the JSON body).

| Method | Endpoint           | Description                                           |
| ------ | ------------------ | ----------------------------------------------------- |
| GET    | `/cart`            | Get current cart (creates one if missing)             |
| DELETE | `/cart`            | Clear all items (`204`); cart row stays               |
| POST   | `/cart/items`      | Add product (or increase quantity if already in cart) |
| PATCH  | `/cart/items/{id}` | Set item quantity                                     |
| DELETE | `/cart/items/{id}` | Remove item (`204 No Content`)                        |

**Guest headers:**

```text
X-Cart-Token: <uuid>
Accept: application/json
Content-Type: application/json
```

**Logged-in headers:**

```text
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

While logged in, `X-Cart-Token` is ignored for resolving the cart.

**POST `/cart/items` body:**

```json
{
  "product_id": 1,
  "quantity": 1
}
```

**PATCH `/cart/items/{id}` body:**

```json
{
  "quantity": 3
}
```

POST and PATCH return **422** (`Not enough stock.`) if the quantity is greater than the product's `stock`. For POST, that includes quantity already in the cart.

**Cart response fields:** `id`, `items`, `total`

**Cart item fields:** `id`, `quantity`, `product` (`id`, `name`, `slug`, `price`, `image`), `subtotal`

**Guest flow:**

1. `GET /cart` (no Bearer) → read `X-Cart-Token` from response headers
2. `POST /cart/items` with that token
3. `GET /cart` again to see items and total

**Merge on login/register:**

Send the guest `X-Cart-Token` on `POST /login` or `POST /register`. Guest items move to the user cart (same product → quantities are summed). The guest cart is deleted. After logout, the user cart stays on the account; guest starts empty (or with a new token).

Seed cart token (optional, for guest testing):

```text
00000000-0000-4000-8000-000000000001
```

### Delivery methods

Public list of **active** delivery options (no auth). Used at checkout.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/delivery-methods` | Active delivery methods |

**Fields:** `id`, `name`, `description`, `price`, `free_over`, `eta_days_min`, `eta_days_max`, `is_active`

Seeded defaults: **Pickup in store** (`price` 0) and **Delivery to address** (`price` 500, free when cart subtotal ≥ `free_over` 5000).

### Payment methods

Public list of **active** payment options (no auth). Used at checkout. Any active payment method may be combined with any active delivery method (no pairing rules yet).

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/payment-methods` | Active payment methods |

**Fields:** `id`, `key`, `name`, `description`, `is_active`

Seeded defaults: **Cash on delivery** (`cash_on_delivery`) and **Stripe** (`stripe`).

### Stripe webhook

Public endpoint (no auth). Stripe CLI locally: `stripe listen --forward-to localhost:8000/api/v1/stripe/webhook`. Set `STRIPE_WEBHOOK_SECRET` from the CLI `whsec_...` value.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| POST | `/stripe/webhook` | Stripe events (signature verified) |

On `payment_intent.succeeded`, the order from Intent metadata `order_id` gets `payment_status: paid` and the confirmation email is sent with an invoice PDF attachment. On `payment_intent.payment_failed`, a still-`pending` order is set to `payment_status: failed` (no email). On `charge.refunded`, `payment_status` is set to `refunded` if not already (Dashboard refunds; admin API refund also updates the order directly).

Env: `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` (see `.env.example`).

### Orders

Logged-in users only (`auth:sanctum`). Guest checkout is **not** supported — login/register first (cart merge applies). Checkout builds the order from the **user cart**, then clears cart items.

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/orders` | Place order from current cart |
| GET | `/orders` | List my orders (summary, paginated) |
| GET | `/orders/{id}` | Order detail (own orders only) |

Shipping is taken from the **user profile** when the body is empty. Body fields override profile. Incomplete profile (missing phone/address) → **422**. `customer_phone` must be E.164 (same as profile); spaces/dashes are stripped.

`delivery_method_id` and `payment_method_id` are **required**. Both must exist and be active; otherwise **422**. Checkout snapshots `delivery_method_name`, `delivery_price` (0 if subtotal ≥ `free_over`), `payment_method_name`, `payment_status` (`pending`), and `currency` from settings (`shop.currency`, default `EUR`). Order `total` = cart subtotal + `delivery_price` (prices are tax-inclusive; VAT is extracted for display only and does not increase `total`). Each item snapshots `tax_name`, `tax_rate`, `tax_amount` from the product tax or the default tax; order `tax_amount` is the sum of item VAT. Fulfillment `status` and `payment_status` are separate fields.

If the payment method `key` is `stripe`, checkout also creates a Stripe PaymentIntent, stores `stripe_payment_intent_id` / `stripe_client_secret`, and returns top-level `client_secret` for confirming payment. Stripe checkout does **not** send email yet; email goes out when the webhook marks the order `paid`. COD still emails immediately on place. If Stripe fails to create the Intent, stock is restored and the order is set to `failed` / `payment_status: failed` (**502**).

**POST `/orders` body** (`delivery_method_id` and `payment_method_id` required; shipping optional if profile is complete):

```json
{
  "delivery_method_id": 2,
  "payment_method_id": 1,
  "customer_name": "Ana Anic",
  "customer_phone": "+381641234567",
  "shipping_address": "Bulevar 1",
  "city": "Beograd",
  "state": "Srbija",
  "zip": "11000",
  "country": "RS"
}
```

**List (`GET /orders`) fields:** `id`, `status`, `total`, `currency`, `items_count`, `delivery_method_name`, `payment_method_name`, `payment_status`, `created_at`

**Detail / create response fields:** `id`, `status`, `total`, `tax_amount`, `currency`, delivery + payment snapshot fields (including `payment_status`), address fields, `items` (`product_id`, `product_name`, `price`, `original_price` nullable when purchased on sale, `quantity`, `subtotal`, `tax_name`, `tax_rate`, `tax_amount`), timestamps

Successful create also returns `"message": "Order placed successfully."` and status **201**. Empty cart → **422**. Not enough stock → **422**; product `stock` is decremented inside a transaction (`lockForUpdate`). For COD, an order confirmation email is sent immediately (Mailpit locally) without PDF while payment is still `pending`. For Stripe, the same email style is sent after payment succeeds (webhook), with invoice PDF attached (`invoice-{id}.pdf`). On every successful checkout (COD and Stripe), an admin notification email is also sent to `shop.email` (skipped if that setting is empty).

### Admin

Requires `Authorization: Bearer {token}` and `role = admin` (`auth:sanctum` + `admin`). Base path: `/api/v1/admin`.

#### Uploads

Shared image upload for admin resources. Returns a storage path to save on category/product as `image`.

| Method | Endpoint   | Description                                      |
| ------ | ---------- | ------------------------------------------------ |
| POST   | `/uploads` | Upload image (`201`); multipart `form-data` only |

**form-data fields:**

| Field      | Required | Description                                      |
| ---------- | -------- | ------------------------------------------------ |
| `file`     | yes      | Image (`jpeg`, `png`, `jpg`, `webp`), max 2MB    |
| `folder`   | yes      | `categories` or `products`                       |
| `filename` | no       | Optional basename (`phones` → `phones.jpg`); omit for random name |

**Response:**

```json
{
  "path": "categories/phones.jpg",
  "url": "http://localhost:8000/storage/categories/phones.jpg"
}
```

Flow: upload → copy `path` → send as `image` on create/update category (JSON). Files are not deleted from disk when a category is updated or removed.

#### Categories

| Method | Endpoint                    | Description                                      |
| ------ | --------------------------- | ------------------------------------------------ |
| GET    | `/categories`               | Paginated list (`per_page`, `sort`, `order`)     |
| POST   | `/categories`               | Create category                                  |
| GET    | `/categories/{slug}`        | Show category                                    |
| PUT/PATCH | `/categories/{slug}`     | Update category (partial with `PATCH`)           |
| DELETE | `/categories/{slug}`        | Delete if it has no products (`204`); else `422` |

**Query (`GET /categories`):** `per_page` (1–50, default 10), `sort` (`name` \| `products_count`), `order` (`asc` \| `desc`).

**Create/update body (JSON):** `name`, `slug` (lowercase, numbers, hyphens), `description` (nullable), `image` (nullable string path from uploads).

#### Products

| Method | Endpoint                 | Description                                      |
| ------ | ------------------------ | ------------------------------------------------ |
| GET    | `/products`              | Paginated list (includes inactive products)      |
| POST   | `/products`              | Create product                                   |
| GET    | `/products/{slug}`       | Show product                                     |
| PUT/PATCH | `/products/{slug}`    | Update product (partial with `PATCH`)            |
| DELETE | `/products/{slug}`       | Delete product (`204`)                           |

**Query (`GET /products`):** same as public shop — `category`, `search`, `per_page`, `sort` (`name` \| `price` \| `created_at`), `order`.

**Create/update body (JSON):** `category_id`, `name`, `slug`, `description` (nullable), `price`, `sale_price` (nullable; must be less than `price`), `tax_id` (nullable; falls back to default tax at checkout), `stock`, `image` (nullable path from uploads with `folder=products`), `is_active` (optional boolean).

Deleting a product removes it from carts; order items keep `product_name` and set `product_id` to null. Image files stay on disk.

#### Taxes

Admin-managed VAT rates. Product prices are **tax-inclusive**; checkout extracts VAT for info (`tax_amount`) and does not change `total`. One tax may be `is_default` (used when a product has no `tax_id`). Cannot delete the default tax or a tax still assigned to products.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/taxes` | List all taxes |
| POST | `/taxes` | Create |
| GET | `/taxes/{id}` | Show |
| PUT/PATCH | `/taxes/{id}` | Update (partial with `PATCH`) |
| DELETE | `/taxes/{id}` | Delete (`204`); else `422` if default or in use |

**Fields:** `id`, `name`, `rate`, `is_default`, `is_active`, timestamps

**Create/update body:** `name`, `rate` (0–100), `is_default` (optional), `is_active` (optional). Setting `is_default: true` clears the previous default.

Customer → **403**.

#### Orders

Admins see **all** orders. Checkout stays on the customer API (`POST /orders`). Orders are not hard-deleted; change fulfillment `status` and/or `payment_status` instead.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/orders` | Paginated list of all orders |
| GET | `/orders/{id}` | Order detail (any order) |
| PUT/PATCH | `/orders/{id}` | Update `status` and/or `payment_status` |
| POST | `/orders/{id}/refund` | Full Stripe refund (paid Stripe orders only) |

**Query (`GET /orders`):** `per_page` (1–50, default 10), `status` (one of the fulfillment values below), `sort` (`total` \| `created_at`), `order` (`asc` \| `desc`). Invalid `status` → **422**.

**Fulfillment statuses:** `pending`, `completed`, `cancelled`, `failed`, `refunded`. New checkouts start as `pending`.

**Payment statuses:** `pending`, `paid`, `failed`, `refunded`. New checkouts start as `pending` (COD stays pending until admin marks `paid`; Stripe updates to `paid` via webhook).

Moving an order from a held fulfillment status (`pending`, `completed`) to `cancelled`, `failed`, or `refunded` **restores** product stock. The same status again does not double-restore. Changing fulfillment `status` sends an email to the customer (Mailpit locally) with the old/new status, item table, totals, payment, and shipping (same layout as the placed email). Changing `payment_status` to `paid` sends the confirmation email again with an invoice PDF attachment (COD after admin marks paid; Stripe usually already did this via webhook).

**POST `/orders/{id}/refund`:** only when `payment_status` is `paid` and the order has a `stripe_payment_intent_id`. Creates a full Stripe refund, sets `status` + `payment_status` to `refunded`, restores stock, and sends the status-change email. Not allowed / not Stripe paid → **422**. Stripe API error → **502**. Partial refunds are not supported.

**PATCH body** (at least one of `status` or `payment_status` required):

```json
{
  "status": "completed",
  "payment_status": "paid"
}
```

Customer → **403**.

#### Delivery methods

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/delivery-methods` | List all methods (including inactive) |
| POST | `/delivery-methods` | Create |
| GET | `/delivery-methods/{id}` | Show |
| PUT/PATCH | `/delivery-methods/{id}` | Update (partial with `PATCH`) |
| DELETE | `/delivery-methods/{id}` | Delete (`204`) |

**Create/update body:** `name`, `description` (nullable), `price`, `free_over` (nullable), `eta_days_min` / `eta_days_max` (nullable), `is_active` (optional).

Customer → **403**.

#### Payment methods

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/payment-methods` | List all methods (including inactive) |
| POST | `/payment-methods` | Create |
| GET | `/payment-methods/{id}` | Show |
| PUT/PATCH | `/payment-methods/{id}` | Update (partial with `PATCH`) |
| DELETE | `/payment-methods/{id}` | Delete (`204`) |

**Create/update body:** `name`, `description` (nullable), `is_active` (optional).

Customer → **403**.

#### Settings

Shop-wide configuration. Admin reads and updates key/value pairs. Keys are defined in code (`Setting::KEYS`); admin can only change values, not add new keys.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/settings` | Return all settings as `{ key: value }` map |
| PATCH | `/settings` | Bulk update one or more settings |

**PATCH body:**

```json
{
  "settings": [
    { "key": "shop.name", "value": "My Shop" },
    { "key": "shop.theme_color", "value": "#ff0000" }
  ]
}
```

Only keys from `Setting::KEYS` are accepted (others → **422**). `value` can be `null`. Keys not sent are not changed.

**Available keys:** `shop.name`, `shop.email`, `shop.phone`, `shop.address_line1`, `shop.address_line2`, `shop.city`, `shop.state`, `shop.zip`, `shop.country`, `shop.logo_url`, `shop.theme_color`, `shop.currency`, `shop.locale`, `shop.timezone`, `shop.orders_per_page`, `shop.products_per_page`.

Customer → **403**.

#### Users

List and manage registered users. No create or delete — registration stays on the public API; hard delete stays on `DELETE /profile`.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/users` | Paginated list of all users |
| GET | `/users/{id}` | User detail |
| PUT/PATCH | `/users/{id}` | Update `is_active` only |

**Query (`GET /users`):** `per_page` (1–50, default 10), `active` (`1` = active, `0` = inactive; omit for all), `sort` (`role` \| `created_at`), `order` (`asc` \| `desc`).

**Response fields:** `id`, `name`, `email`, `role`, `is_active`, `email_verified_at`, `phone`, shipping fields, `created_at`.

**PATCH body:**

```json
{
  "is_active": false
}
```

Admin cannot change their own `is_active` → **403**. Setting `is_active` to `false` revokes all Sanctum tokens for that user (existing Bearer tokens stop working). Customer → **403**.

### Errors

API routes return JSON. Missing resources respond with:

```json
{
  "message": "Not found."
}
```

Status code: `404`

## Getting started

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# configure MySQL in .env
php artisan migrate
php artisan storage:link
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=CartSeeder
php artisan serve
```

### Root (commit hooks)

```bash
npm install
```

Uses Husky + Commitlint for conventional commits.

### Frontend

```bash
cd frontend
npm install
npm run dev
```

## Later polish

Things we skipped on purpose (MVP). Do these before production / when polishing:

### Auth

- [ ] Optional: revoke other tokens after change-password
- [ ] Refresh-token style flow (only if needed; Sanctum is usually enough)

### Admin / tooling

- [ ] PHPStan / Larastan (unused imports, static analysis)
- [ ] API tests (Feature tests for auth, cart, orders)

### Frontend

- [ ] React storefront (Vite)

## Commit convention

Use [Conventional Commits](https://www.conventionalcommits.org/):

```text
feat: add cart endpoints
fix: correct product sort whitelist
docs: update readme file
chore: initial setup
```

README-only changes: `docs: update readme file`
