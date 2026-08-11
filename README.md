# LR Shop

E-commerce platform built with Laravel and React.

## Stack

- **Backend:** Laravel 12, MySQL
- **Frontend:** React, Vite

## Structure

```text
LR--Shop/
├── backend/     # REST API
└── frontend/    # Storefront
```

## Features

- Categories
- Products
- Shop catalog
- Cart

Authentication, orders, and admin follow in later phases.

## Getting started

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# configure MySQL in .env
php artisan migrate --seed
php artisan serve
```

API: `http://localhost:8000`

### Frontend

```bash
cd frontend
npm install
npm run dev
```
