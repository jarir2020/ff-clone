# Falaq Food Clone Plan

No files have been changed. I inspected the live storefront and the five supplied ecommerce projects.

## Findings

The live site is an API-driven, server-rendered Angular storefront, but we can reproduce its appearance using Vue 3 and Tailwind.

The public site includes:

- Green branded header with category navigation, search, sign-in, offers, cart, and mobile bottom navigation. [Homepage](https://falaqfood.com/)
- Hero carousel, promotional banners, featured categories, and product cards.
- Product listing pages with price filters, stock filters, sorting, discounts, pagination, and variant selection. [Honey category](https://falaqfood.com/product-category/honey)
- Product pages with gallery, Bengali/English descriptions, discount pricing, quantity control, WhatsApp, phone ordering, delivery/quality badges, FAQ, and related products. [Product example](https://falaqfood.com/product/sundarban-honey-1kg)
- About, blog, contact, authentication, offers, and customer-account pages. [About](https://falaqfood.com/about-us), [Blog](https://falaqfood.com/blog), [Contact](https://falaqfood.com/contact-us)
- Public catalog APIs for categories, products, and banners. [Categories API](https://api.falaqfood.com/api/v1/storefront/catalog/categories/featured), [Products API](https://api.falaqfood.com/api/v1/storefront/catalog/featured-products), [Banners API](https://api.falaqfood.com/api/v1/storefront/cms/banners)

Observed design tokens include:

- Maximum content width: approximately `1280px`
- Main brand green: `#179d55`
- Dark green: `#107340`
- Primary font: Noto Sans
- Heading font: Georgia-style serif
- Desktop header height: approximately `90px`
- Mobile bottom bar: approximately `78px`
- Rounded cards, product image containers, green action buttons, Bengali/English content

## Recommended ecommerce base

Use:

```text
/home/jarir-ahmed/Music/Windows Essentials/Copyables/All Code 3/Ecommerce/New/Ecommerce5/ecommerce5
```

Reason:

- Laravel 12 and PHP 8.2 foundation
- Existing products, variants, categories, orders, carts, customers, coupons, reviews, blogs, banners, shipping, reports, roles, permissions, payments, courier, SMS, and admin modules
- Includes newer feature/banner/social-login functionality
- Strongest recent storefront/admin additions among the supplied versions

`ecommerce5_2` appears to be the same project lineage with additional runtime/cache state. `Ecommerce4` contains size-chart functionality that can be ported later if required.

The base uses Blade, Bootstrap, and Sass rather than Vue and Tailwind, so its backend/domain code should be reused carefully while the public storefront is rebuilt in Vue.

## Target architecture

### Backend

- Laravel 12
- MySQL or MariaDB
- Laravel Sanctum or session authentication
- Eloquent models and service classes
- Laravel Form Requests and API Resources
- Spatie roles and permissions
- Laravel queues for email, SMS, image processing, and notifications
- Storage abstraction for local storage, S3, or CDN/R2
- REST endpoints for products, categories, cart, checkout, orders, blog, contact, and customer accounts

### Frontend

- Vue 3
- Inertia.js with optional SSR for SEO-friendly public pages
- Vite
- Tailwind CSS
- Pinia for cart, customer, search, filter, and UI state
- Axios or Inertia requests
- Reusable Vue components for cards, drawers, modals, filters, product galleries, and forms

### Admin

Reuse the Ecommerce5 Laravel admin controllers, models, policies, migrations, and Blade screens initially.

Then customize:

- Admin branding
- Product and variant management
- Banner placement management
- Featured-category management
- Homepage content management
- Order workflow
- Blog and page management
- Coupon and offer management
- Customer management
- Shipping and courier settings
- Payment/SMS/email settings
- Roles and permissions

A Vue/Tailwind admin interface can be introduced later without replacing the backend.

## Frontend component plan

### Global shell

- Announcement/top utility bar
- Desktop header
- Category navigation
- Search box with suggestions/results
- Sign-in/account action
- Quick Action drawer
- Mobile navigation drawer
- Floating chat/WhatsApp action
- Floating cart action
- Cart drawer
- Mobile bottom navigation
- Shared footer

### Homepage

- Hero carousel
- Right-side promotional banner stack
- Featured category row
- Product section heading and controls
- Featured product grid
- Discount badges
- Add-to-cart feedback
- Footer contact and social areas

### Catalog pages

Routes:

```text
/shop
/product-category/{slug}
/offers
```

Features:

- Desktop filter sidebar
- Mobile filter drawer
- Minimum/maximum price fields
- On-sale and in-stock filters
- Sort dropdown
- Responsive product grid
- Discount badges
- Stock-out state
- Variant selection modal
- Pagination
- Empty and loading states

### Product page

Route:

```text
/product/{slug}
```

Features:

- Product image gallery
- Product title and bilingual content
- Current price and compare-at price
- Discount percentage
- Variant selector
- Quantity controls
- Add Cart and Buy Now
- Call-to-order action
- WhatsApp action
- Delivery, COD, and quality badges
- Long-form description
- Tables, lists, FAQ accordions, and related products

### Customer flows

- Login
- Registration
- Password reset
- Customer profile
- Address management
- Order history
- Order detail
- Cart
- Checkout
- Order confirmation
- Order tracking

### Content pages

- About Us
- Blog listing
- Blog detail
- Contact form
- Shipping and returns
- Careers
- Corporate deals
- Combo products
- SEO landing pages

## Backend data model

Reuse equivalent models from Ecommerce5 and normalize them around:

- `products`
- `product_variants`
- `product_images`
- `categories`
- `brands`
- `banners`
- `banner_positions`
- `homepage_sections`
- `blog_posts`
- `pages`
- `customers`
- `addresses`
- `carts`
- `cart_items`
- `orders`
- `order_items`
- `payments`
- `shipments`
- `shipping_zones`
- `coupons`
- `reviews`
- `media`
- `settings`
- `contact_messages`
- `newsletter_subscribers`

Important Falaq-specific fields:

- Bengali and English product names
- Bengali and English descriptions
- BDT pricing
- Compare-at pricing
- Variant sizes and weights
- Stock status
- Featured position
- Promotional labels
- Banner placement such as hero-left, hero-right-top, and hero-right-bottom
- Product SEO title, description, slug, and canonical URL

## Implementation phases

### Phase 1 — Baseline audit

- Capture desktop and mobile screenshots of the live site
- Record exact page URLs and interaction states
- Document typography, spacing, colors, breakpoints, images, icons, and animations
- Confirm which content and media may legally be reused
- Produce a visual reference checklist

The current environment did not have the browser automation binary, so the first implementation phase should include Playwright-based screenshots at 1440px, 1280px, 1024px, 768px, 390px, and 375px widths.

### Phase 2 — Project foundation

- Create a clean working copy from Ecommerce5
- Remove copied `.env`, runtime cache, sessions, logs, and generated files
- Audit dependencies and security-sensitive code
- Add Vue 3, Inertia, Tailwind, Pinia, and Vite configuration
- Establish Laravel route and API conventions
- Add seed data and development fixtures

### Phase 3 — Visual system

- Implement Falaq color tokens
- Add local Noto Sans and heading font configuration
- Implement global container, spacing, border radius, shadows, and buttons
- Build the shared header, footer, drawers, cart, icons, and mobile navigation
- Match responsive behavior before implementing individual pages

### Phase 4 — Homepage and content pages

- Build hero carousel and banner grid
- Build featured category section
- Build product cards and featured products
- Build About, Blog, Contact, Offers, and Corporate pages
- Connect homepage content to admin-managed records

### Phase 5 — Commerce functionality

- Implement catalog filters and pagination
- Implement product details and variants
- Implement cart drawer and cart page
- Implement checkout and COD
- Add payment gateway integrations only after the base checkout is stable
- Add SMS, email, WhatsApp, courier, and order-status flows

### Phase 6 — Admin customization

- Rebrand the existing admin panel
- Add Falaq-specific dashboard statistics
- Add homepage/banner/category controls
- Improve product and variant management
- Configure shipping, coupons, orders, customers, pages, blog, SEO, and settings
- Add role-based access for administrators, staff, delivery staff, and vendors if needed

### Phase 7 — Data and media migration

- Import categories and products from an authorized source
- Import variants, prices, discounts, stock, descriptions, and SEO metadata
- Migrate or re-upload images to controlled storage/CDN
- Preserve image aspect ratios and responsive crops
- Validate imported Bengali text and HTML descriptions
- Seed initial homepage banners and featured products

### Phase 8 — Visual and functional QA

- Compare screenshots against the live site
- Test desktop, tablet, and mobile layouts
- Test header menus, search, filters, variant selection, cart, checkout, login, blog, and contact forms
- Test stock-out, empty-cart, empty-search, validation, and API-error states
- Check accessibility, keyboard navigation, focus states, image alt text, and contrast
- Check performance, lazy loading, SEO metadata, sitemap, canonical URLs, and structured data

### Phase 9 — Deployment

- Configure production `.env` without committing credentials
- Build Vue/Tailwind assets
- Configure storage and CDN
- Run migrations and seeders safely
- Configure queues, scheduler, mail, SMS, payment, courier, and backups
- Verify the actual live domain after deployment
- Perform a final screenshot comparison and end-to-end order test

## Definition of done

The clone will be accepted only when:

- Public pages match the reference layout at agreed viewport sizes
- Typography, colors, spacing, images, responsive behavior, drawers, and mobile navigation match
- Product, category, blog, account, cart, checkout, and contact flows work
- Homepage banners and featured products are editable from the admin panel
- Orders, stock, discounts, variants, shipping, and customer data are persisted correctly
- SEO metadata and URLs are preserved
- No credentials, runtime cache, or unrelated script data are copied into the project
- Live deployment is tested separately from local build success

The next step should be approval of this architecture, followed by Phase 1 baseline capture and a detailed route/component inventory before importing the Ecommerce5 code.