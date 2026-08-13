# LR Shop

E-commerce platform built with Laravel and React.

## Stack

- **Backend:** Laravel 12, MySQL, Sanctum (installed, not used yet)
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
- Category and product seed data
- API Resources for JSON responses
- Route model binding by slug
- Product filtering, search, sort, pagination
- Clean JSON 404 responses for API routes

Planned:

- Cart
- Authentication
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
