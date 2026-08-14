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
- Auth API (register, login, logout, forgot/reset/change password)
- User carts (`user_id`) and guest cart merge on login/register
- Category, product, and cart seed data
- API Resources for JSON responses
- Route model binding by slug
- Product filtering, search, sort, pagination
- Clean JSON 404 responses for API routes

Planned:

- Orders
- Admin

## API

Base URL: `http://localhost:8000/api/v1`

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/categories` | List all categories |
| GET | `/categories/{slug}` | Single category |

**Category fields:** `id`, `name`, `slug`, `description`, `image`, `products_count`

### Products

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/products` | List active products (paginated) |
| GET | `/products/{slug}` | Single product |

**Product fields:** `id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `is_active`, `created_at`, `updated_at`, `category`

**Query parameters (index only):**

| Param | Example | Description |
|-------|---------|-------------|
| `category` | `laptops` | Filter by category slug |
| `search` | `phone` | Search in name and description |
| `sort` | `price` | Sort field: `name`, `price`, `created_at` (default: `name`) |
| `order` | `desc` | Sort direction: `asc` or `desc` (default: `asc`) |
| `per_page` | `20` | Items per page, 1–50 (default: `10`) |

**Examples:**

```text
GET /api/v1/products
GET /api/v1/products?category=laptops
GET /api/v1/products?search=illo&sort=price&order=desc&per_page=20
GET /api/v1/products/macbook-pro
GET /api/v1/categories/phones
```

### Auth

Sanctum **Bearer** tokens. Register and login return the token in `data.token`. Send it on protected routes:

```text
Authorization: Bearer {token}
Accept: application/json
```

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/register` | no | Create account, return token |
| POST | `/login` | no | Return token |
| POST | `/logout` | yes | Revoke current token |
| POST | `/forgot-password` | no | Send reset link (Mailpit locally) |
| POST | `/reset-password` | no | Set new password using email token |
| POST | `/change-password` | yes | Change password while logged in |
| GET | `/api/user` | yes | Current user (Laravel default, not under `/v1`) |

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

| Client | How cart is resolved |
|--------|----------------------|
| Guest | `X-Cart-Token` header (UUID) |
| Logged in | `Authorization: Bearer {token}` → cart with that `user_id` |

The cart token is returned in the **response header** `X-Cart-Token` (not in the JSON body).

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/cart` | Get current cart (creates one if missing) |
| POST | `/cart/items` | Add product (or increase quantity if already in cart) |
| PATCH | `/cart/items/{id}` | Set item quantity |
| DELETE | `/cart/items/{id}` | Remove item (`204 No Content`) |

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

## Commit convention

Use [Conventional Commits](https://www.conventionalcommits.org/):

```text
feat: add cart endpoints
fix: correct product sort whitelist
docs: update readme file
chore: initial setup
```

README-only changes: `docs: update readme file`
