# AGENTS.md

## Project overview

`ff-clone` is a Laravel 12 ecommerce application with a Vue 3 and Tailwind CSS storefront. The backend and admin panel use Laravel controllers, Blade views, Eloquent models, and MySQL migrations. Vite builds the Vue/Tailwind assets.

## Development commands

- Install PHP dependencies: `composer install`
- Install frontend dependencies: `npm install`
- Build production assets: `npm run build`
- Run the Laravel server: `php artisan serve`
- Run migrations: `php artisan migrate`
- Run tests: `php artisan test`

Use the project `.env` for local configuration. Never commit `.env`, `credentials.txt`, API keys, payment credentials, courier tokens, or generated uploads.

## Change guidelines

- Preserve the Falaq Food storefront visual language and responsive behavior when changing frontend components.
- Prefer existing Vue components, Blade partials, Tailwind utilities, and admin patterns before introducing a new system.
- Add database changes as reversible, idempotent migrations. Do not use destructive schema commands against a shared database.
- Keep payment and courier integrations inactive until real credentials have been configured explicitly.
- Avoid unrelated formatting or generated-file changes.
- Validate Laravel syntax/routes, migrations, and the affected browser flow after changes.

## Repository safety

Before committing, review `git diff` and `git status`. Keep local credentials and machine-specific files untracked. Use scoped commits with a clear message.
