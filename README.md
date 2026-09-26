# ff-clone

Laravel/Vue/Tailwind ecommerce storefront and admin panel inspired by the Falaq Food website.

This repository is an independent implementation and is not an official Falaq Food product. Brand assets, product images, copy, and other third-party content may have separate ownership or usage requirements.

## Stack

- Laravel 12 and PHP 8.2+
- MySQL
- Vue 3 and Vite
- Tailwind CSS 3
- Blade-based Laravel admin panel

## Main features

- Responsive storefront with animated search, catalog, product details, cart, checkout, blog, offers, combos, reviews, video gallery, footer, and floating contact/cart controls.
- Customer account and order flows.
- Vendor, reseller, delivery, courier, payment, coupon, complaint, contact-message, purchase, expense, and fund-management workflows.
- Custom Laravel admin panel with ecommerce operations and configuration screens.

## Local setup

Requirements: PHP 8.2+, Composer, Node.js/npm, and MySQL.

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure the database and other local values in `.env`, then run:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

Open `http://127.0.0.1:8000/`. The admin login is available at `/login`; the customer login is available at `/account/login`.

For frontend hot reload, use a second terminal:

```bash
npm run dev
```

Local demo credentials, when seeded, are kept in the ignored `credentials.txt` file and are intentionally not documented in Git.

## Useful checks

```bash
php artisan route:list
php artisan migrate:status
php artisan test
npm run build
```

Keep payment gateways and courier integrations disabled until valid credentials and production endpoints have been configured. Never commit `.env`, credentials, API keys, tokens, or generated private data.

## Project structure

- `app/` — Laravel application code, controllers, models, services, and helpers
- `database/` — migrations, factories, and seeders
- `resources/views/` — Blade storefront and admin templates
- `resources/js/` — Vue storefront components and commerce state
- `public/` — public assets and upload targets
- `routes/` — web and API route definitions
- `plan.md` — implementation plan and phase notes

See [AGENTS.md](AGENTS.md) for contributor and coding-agent guidance.

## License

Project source is released under the [MIT License](LICENSE), subject to any separate rights for third-party or brand-specific assets and content.
