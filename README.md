# Product Catalog

A simple e-commerce product catalog built with plain PHP (no frameworks), MySQL, and Bootstrap 5.

## Features

- Categories sidebar with product counts
- Product cards with AJAX filtering by category
- Sorting: newest first, cheapest first, alphabetical — without page reload
- "Buy" button opens a Bootstrap modal with product details
- URL GET parameters persist selected category and sort order across page reloads
- Browser back/forward navigation support

## Tech Stack

- **Backend:** PHP 8.2+ (OOP, no frameworks)
- **Database:** MySQL 8.0 (Docker)
- **Frontend:** Bootstrap 5.3, vanilla JavaScript, Bootstrap Icons
- **Dev tools:** PHPStan, PHP CS Fixer

## Requirements

- PHP 8.2+ with `pdo_mysql` extension
- Docker & Docker Compose
- Composer (dev dependencies only)

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/v0idRift/product-catalog.git
cd product-catalog
```

### 2. Install dev dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
```

Edit `.env` if your database credentials differ from the defaults.

### 4. Start the database

```bash
docker compose up -d
```

This starts a MySQL 8.0 container on port **3307** and automatically creates the `categories` database with tables and
seed data (4 categories, 16 products).

### 5. Start the dev server

```bash
php -S localhost:8000
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

## Project Structure

```
├── api/
│   └── products.php        # JSON API endpoint for AJAX requests
├── assets/
│   ├── css/style.css        # Custom styles
│   └── js/app.js            # Frontend logic (AJAX, modal, sorting)
├── config/
│   └── Database.php         # Database singleton (PDO)
├── models/
│   ├── Category.php         # Category model
│   └── Product.php          # Product model
├── sql/
│   └── init.sql             # Schema + seed data (Docker entrypoint)
├── docker-compose.yml
├── composer.json
└── index.php                # Main page
```

## Code Quality

```bash
# Static analysis
vendor/bin/phpstan analyse

# Code style
vendor/bin/php-cs-fixer fix --dry-run --diff
```
