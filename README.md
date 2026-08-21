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
- Auth API (register, email verify, resend verify, login, logout, forgot/reset/change password)
- User carts (`user_id`) and guest cart merge on login/register
- Orders API (checkout, list, show — auth required; shipping from profile)
- Delivery methods (public list + admin CRUD); checkout requires `delivery_method_id` with price/`free_over` snapshot
- Payment methods (public list + admin CRUD); checkout requires `payment_method_id` with name snapshot (Cash on delivery / Stripe placeholder; real Stripe later)
- Order currency snapshot from `shop.currency` settings at checkout
- Order confirmation email on checkout (Mailpit locally)
- Order status change email to customer when admin updates status (Mailpit locally)
- CORS configured via `FRONTEND_URL` env variable (default `http://localhost:5173`)
- Profile API (GET/PATCH/DELETE — hard delete; admins blocked)
- E.164 phone validation on profile and checkout
- Clear cart (`DELETE /cart`)
- User roles (`customer` / `admin`) and admin middleware
- User `is_active` flag; inactive users cannot log in
- Admin uploads, category CRUD, product CRUD, order status, user management, shop settings, delivery methods, and payment methods
- Product stock: cart cannot exceed stock; checkout decrements; cancel/fail/refund restores
- Category, product, and cart seed data
- API Resources for JSON responses
- Route model binding by slug
- Product filtering, search, sort, pagination
- Clean JSON 404 responses for API routes

Planned:

- Stripe test payments

## API

Base URL: `http://localhost:8000/api/v1`

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

**Product fields:** `id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `is_active`, `created_at`, `updated_at`, `category`

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

**Fields:** `id`, `name`, `description`, `is_active`

Seeded defaults: **Cash on delivery** and **Stripe** (selection only for now; Stripe charge/webhook later).

### Orders

Logged-in users only (`auth:sanctum`). Guest checkout is **not** supported — login/register first (cart merge applies). Checkout builds the order from the **user cart**, then clears cart items.

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/orders` | Place order from current cart |
| GET | `/orders` | List my orders (summary, paginated) |
| GET | `/orders/{id}` | Order detail (own orders only) |

Shipping is taken from the **user profile** when the body is empty. Body fields override profile. Incomplete profile (missing phone/address) → **422**. `customer_phone` must be E.164 (same as profile); spaces/dashes are stripped.

`delivery_method_id` and `payment_method_id` are **required**. Both must exist and be active; otherwise **422**. Checkout snapshots `delivery_method_name`, `delivery_price` (0 if subtotal ≥ `free_over`), `payment_method_name`, and `currency` from settings (`shop.currency`, default `EUR`). Order `total` = cart subtotal + `delivery_price`.

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

**List (`GET /orders`) fields:** `id`, `status`, `total`, `currency`, `items_count`, `delivery_method_name`, `payment_method_name`, `created_at`

**Detail / create response fields:** `id`, `status`, `total`, `currency`, delivery + payment snapshot fields, address fields, `items` (`product_id`, `product_name`, `price`, `quantity`, `subtotal`), timestamps

Successful create also returns `"message": "Order placed successfully."` and status **201**. Empty cart → **422**. Not enough stock → **422**; product `stock` is decremented inside a transaction (`lockForUpdate`). An order confirmation email is sent to the user's address (Mailpit locally) with: item table (product, qty, unit price, subtotal), then a separate totals block (subtotal, delivery, total) with currency, payment method, shipping address, and shop name from settings (`shop.name`).

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

**Create/update body (JSON):** `category_id`, `name`, `slug`, `description` (nullable), `price`, `stock`, `image` (nullable path from uploads with `folder=products`), `is_active` (optional boolean).

Deleting a product removes it from carts; order items keep `product_name` and set `product_id` to null. Image files stay on disk.

#### Orders

Admins see **all** orders. Checkout stays on the customer API (`POST /orders`). Orders are not hard-deleted; change `status` instead.

| Method | Endpoint | Description |
| ------ | -------- | ----------- |
| GET | `/orders` | Paginated list of all orders |
| GET | `/orders/{id}` | Order detail (any order) |
| PUT/PATCH | `/orders/{id}` | Update `status` only |

**Query (`GET /orders`):** `per_page` (1–50, default 10), `status` (one of the values below), `sort` (`total` \| `created_at`), `order` (`asc` \| `desc`). Invalid `status` → **422**.

**Statuses:** `pending`, `processing`, `completed`, `cancelled`, `failed`, `refunded`. New checkouts start as `pending`.

Moving an order from a held status (`pending`, `processing`, `completed`) to `cancelled`, `failed`, or `refunded` **restores** product stock. The same status again does not double-restore. Every status change sends an email to the customer (Mailpit locally) with the old and new status plus order items.

**PATCH body:**

```json
{
  "status": "processing"
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

### Cart / Orders

- [ ] Payment (Stripe / etc.)

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
