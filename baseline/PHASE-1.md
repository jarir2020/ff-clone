# Phase 1: Live Falaq Food Baseline

Captured on 2026-09-26 from `https://falaqfood.com/` before importing or modifying the Laravel foundation.

## Capture status

- 10 representative routes captured at desktop width `1440px`.
- The same 10 routes captured at responsive mobile width `390px`.
- All 10 routes returned HTTP `200` during the baseline check.
- Full-page screenshots are stored in `baseline/screenshots/`.
- Mobile captures use a `390px` responsive viewport; they are not a device/user-agent simulation.

## Screenshot manifest

| Route | Desktop | Mobile |
| --- | --- | --- |
| `/` | `home-desktop.png` | `home-mobile.png` |
| `/shop` | `shop-desktop.png` | `shop-mobile.png` |
| `/product-category/honey` | `product-category-honey-desktop.png` | `product-category-honey-mobile.png` |
| `/product/sundarban-honey-1kg` | `product-sundarban-honey-1kg-desktop.png` | `product-sundarban-honey-1kg-mobile.png` |
| `/offers` | `offers-desktop.png` | `offers-mobile.png` |
| `/about-us` | `about-us-desktop.png` | `about-us-mobile.png` |
| `/blog` | `blog-desktop.png` | `blog-mobile.png` |
| `/contact-us` | `contact-us-desktop.png` | `contact-us-mobile.png` |
| `/account/login` | `account-login-desktop.png` | `account-login-mobile.png` |
| `/account/register` | `account-register-desktop.png` | `account-register-mobile.png` |

## Observed route families

### Storefront

- `/`
- `/shop`
- `/offers`
- `/product-category/{slug}`
- `/product/{slug}`
- `/track-order`
- `/return-refund-policy`

### Content and marketing

- `/about-us`
- `/blog`
- `/career`
- `/corporate-deal`
- `/contact-us`
- `/category/{slug}` and `/tag/{slug}` for content taxonomy
- Root-level blog article slugs, including `/what-is-green-tea`, `/why-eat-rolled-oats-daily`, and `/senna-leaf-powder-for-constipation`

### Account

- `/account/login`
- `/account/register`

The cart appears primarily as a drawer/floating interaction in the static route baseline. Cart, checkout, customer dashboard, and authenticated order states must be captured during the interactive QA pass.

## Live component-to-Vue map

The public HTML exposes Angular custom-element names. These are the implementation boundaries to reproduce in Vue:

| Live component area | Planned Vue boundary |
| --- | --- |
| `app-main-layout`, `app-header`, `app-footer` | `SiteLayout`, `SiteHeader`, `SiteFooter` |
| `app-header-category-bar`, `app-header-search` | `CategoryNav`, `GlobalSearch` |
| `app-mobile-drawer`, `app-bottom-bar` | `MobileNavDrawer`, `MobileBottomBar` |
| `app-hero-banner`, `app-hero-promo-banner-card` | `HeroCarousel`, `PromoBannerCard` |
| `app-featured-categories` | `FeaturedCategoryStrip` |
| `app-featured-products`, `app-product-card` | `FeaturedProducts`, `ProductCard` |
| `app-category-page`, `app-filter-sidebar`, `app-sort-bar`, `app-pagination` | `CatalogPage`, `CatalogFilters`, `CatalogSort`, `Pagination` |
| `app-product-detail`, `app-image-gallery` | `ProductDetailPage`, `ProductGallery` |
| `app-product-purchase-icon`, `app-product-option-icon` | `PurchaseActions`, `ProductVariantSelector` |
| `app-recently-viewed-products` | `RecentlyViewedProducts` |
| `app-blog-page`, `app-blog-card`, `app-blog-sidebar` | `BlogIndexPage`, `BlogCard`, `BlogSidebar` |
| `app-about` | `AboutPage` |
| `app-contact-us`, `app-feedback-modal` | `ContactPage`, `FeedbackModal` |
| `app-login`, `app-register` | `LoginPage`, `RegisterPage` |
| `app-cart-drawer`, `app-floating-cart`, `app-cart-animation` | `CartDrawer`, `FloatingCart`, `CartFeedback` |
| `app-floating-chat` | `FloatingChat` |

## Shared behavior to reproduce

- Desktop and mobile header variants
- Category navigation and search interaction
- Quick-action navigation
- Hero carousel controls and indicators
- Product discount badges and stock-out states
- Product variant selection and quick add-to-cart behavior
- Price and stock filters with mobile drawer behavior
- Cart drawer, quantity controls, subtotal, and empty-cart state
- Floating chat and cart actions
- Mobile bottom navigation
- Shared footer, contact details, social links, and policy links

## Phase 1 implementation handoff

The next implementation step is Phase 2:

1. Create a clean working copy from `Ecommerce5/ecommerce5`.
2. Exclude `.env`, runtime cache, sessions, logs, generated assets, and unrelated uploaded data.
3. Audit the copied Laravel routes, migrations, controllers, models, and admin views.
4. Add Vue 3, Inertia, Tailwind, Pinia, and the first Vite entry point.
5. Implement the shared visual shell against the screenshots before connecting the full catalog.

Do not begin data migration or payment integration until the shared shell and product-card geometry have passed the first desktop/mobile visual comparison.
