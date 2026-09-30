# Acme Widget Co — Sales Basket POC

Proof of concept for Acme Widget Co's sales basket: a Laravel API with a DDD-style layout, a React/TypeScript UI, MySQL, Docker Compose, PHPUnit domain tests, and Playwright end-to-end checks.

## Quick start (Docker)

```bash
docker compose up --build
```

- UI: http://localhost:5173  
- API: http://localhost:8080  

The API container waits for MySQL, then runs migrations and seeds the catalogue, delivery rules, and offers.

## Products

| Product      | Code | Price  |
|--------------|------|--------|
| Red Widget   | R01  | $32.95 |
| Green Widget | G01  | $24.95 |
| Blue Widget  | B01  | $7.95  |

## Business rules

**Delivery** (based on the subtotal **after** offers):

- under $50 → $4.95  
- under $90 → $2.95  
- $90 or more → free  

**Offer:** buy one red widget (`R01`), get the second half price. Each pair of red widgets gets one half-price discount.

### Example totals

| Basket                         | Total  |
|--------------------------------|--------|
| B01, G01                       | $37.85 |
| R01, R01                       | $54.37 |
| R01, G01                       | $60.85 |
| B01, B01, R01, R01, R01        | $98.27 |

## Architecture

```
Http Controllers  →  Application Services  →  Domain
                              ↓
                     Repository ports
                              ↓
              Infrastructure (Eloquent / MySQL)
```

- **Domain** — pure PHP: `Basket`, `ProductCatalogue`, `DeliveryChargeRules`, offers, `Money`
- **Application** — one service per use case (`ListProductsService`, `CalculateBasketTotalService`)
- **Http** — thin controllers + form requests only
- **Infrastructure** — Eloquent models and repositories that implement domain ports

The basket is **stateless**: the UI sends the full list of product codes on each total request. There is no order persistence.

### API

- `GET /api/products`
- `POST /api/basket/total` with body `{ "items": ["B01", "G01"] }`  
  → `{ "total": 37.85, "formatted": "$37.85" }`

## Assumptions

- Money is stored and calculated in **cents** (`int`) to avoid floating-point drift.
- Half-price discounts use half-up rounding to the nearest cent (`32.95 / 2` → `16.48`).
- Delivery is calculated on the amount **after** offers.
- Catalogue, delivery tiers, and offers live in MySQL and are seeded; the domain stays free of Eloquent.
- No authentication, checkout, or order history in this PoC.
- Application code has no docblocks; naming and types carry intent. This README documents architecture and assumptions.

## Tests

```bash
# Domain / PHPUnit (inside API container or locally)
docker compose exec api php artisan test
# or: php artisan test

# Frontend E2E (needs Node 20+; starts local API + Vite)
cd frontend
npm install
npx playwright install chromium
npm run test:e2e
```

## Local development (without Docker UI)

```bash
# API (MySQL required, or switch .env to sqlite for domain-only work)
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

# UI
cd frontend
npm install
npm run dev
```

Vite proxies `/api` to `http://127.0.0.1:8000` by default.

## Docker services

| Service   | Role                                      |
|-----------|-------------------------------------------|
| `mysql`   | MySQL 8                                   |
| `api`     | PHP-FPM + Laravel                         |
| `nginx`   | Serves the Laravel API on port 8080       |
| `frontend`| Built SPA + nginx, proxies `/api` to nginx|
