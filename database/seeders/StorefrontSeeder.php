<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BannerCategory;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CreatePage;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Productimage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class StorefrontSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Dates (খেজুর)', 'slug' => 'dates-খেজুর', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea1ea-4b2b-7aae-a6a4-db1d20856681.webp'],
            ['name' => 'Dry Food', 'slug' => 'dry-food', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f4-25d0-74c4-a281-0703d4cd359b.webp'],
            ['name' => 'Falaq Food Special Masala', 'slug' => 'masala-মসলা-কম্বো', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea1f9-5d9e-7e62-b1bc-2a5a7a4f69bc.webp'],
            ['name' => 'Honey', 'slug' => 'honey', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea206-4e4b-7c0c-a29c-dfa3c6d5fcb9.webp'],
            ['name' => 'Mix food', 'slug' => 'mix-food', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea20d-0c9f-7c19-9f84-9d0d1cb9a60d.webp'],
            ['name' => 'Nuts & Seeds', 'slug' => 'nuts-seeds', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea214-2a88-7d0c-9378-21325cf4b75f.webp'],
            ['name' => 'Tea', 'slug' => 'tea', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ea21c-6ef4-7bb6-b2f8-42b8d2d96d4b.webp'],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $record = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [...$category, 'parent_id' => 0, 'status' => 1]
            );
            $categoryIds[$record->slug] = $record->id;
        }

        $products = [
            ['name' => 'Primal Gold - (প্রাইমাল গোল্ড)', 'slug' => 'primal-gold', 'category' => 'honey', 'new' => 950, 'old' => 1200, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05135-6532-7173-8fce-069b93b0f86a.webp'],
            ['name' => 'Fermented Garlic & Honey (ফার্মেন্টেড গার্লিক হানি) - 450gm', 'slug' => 'fermented-garlic', 'category' => 'honey', 'new' => 780, 'old' => 1000, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05704-1d59-7f17-aa56-70f277bc2547.webp'],
            ['name' => 'রসুনজিরা - (কালোজিরা, রসুন, মধু মিক্স) – 450gm', 'slug' => 'kalojira-garlic-honey', 'category' => 'honey', 'new' => 750, 'old' => 1200, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019ef93a-f9df-7731-9976-0ba2c308205f.webp'],
            ['name' => 'শিলাজিৎ মধু (Shilajit Honey) - 1KG', 'slug' => 'shilajit-honey', 'category' => 'honey', 'new' => 1200, 'old' => 1800, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07628-b6b6-7c3b-b0c4-86408d538941.webp'],
            ['name' => 'জিঞ্জার হানি (Ginger Honey) - 1KG', 'slug' => 'ginger-honey', 'category' => 'honey', 'new' => 850, 'old' => 1200, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/09/01a084ef-ccab-7779-a91c-75eb6a427f9c.webp'],
            ['name' => 'Flavour Box Honey - 2 KG', 'slug' => 'flavour-box-honey', 'category' => 'honey', 'new' => 1600, 'old' => 2500, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b04-4d65-7a3c-b9a5-f0778437fb7e.webp'],
            ['name' => 'সরিষা ফুলের মধু ২ কেজি (Mustard Flower Honey 2kg)', 'slug' => 'mustard-flower-honey', 'category' => 'honey', 'new' => 1050, 'old' => 1400, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/09/01a07b71-b6d0-79f0-8d13-1c31e766f5eb.webp'],
            ['name' => 'প্রিমিয়াম ঘি (Ghee)', 'slug' => 'ghee', 'category' => 'dry-food', 'new' => 900, 'old' => 1050, 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/08/01a05158-d193-749f-9eb3-e77f5b9a0bf9.webp'],
        ];

        foreach ($products as $index => $product) {
            $record = Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'name' => $product['name'],
                    'category_id' => $categoryIds[$product['category']],
                    'product_code' => 'FALAQ-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'purchase_price' => max(1, $product['new'] - 100),
                    'old_price' => $product['old'],
                    'new_price' => $product['new'],
                    'stock' => 100,
                    'description' => '<p>Pure and carefully selected food from Falaq Food.</p>',
                    'meta_description' => $product['name'],
                    'feature_product' => 1,
                    'topsale' => 1,
                    'status' => 1,
                ]
            );

            Productimage::updateOrCreate(
                ['product_id' => $record->id],
                ['image' => $product['image']]
            );
        }

        $bannerCategory = BannerCategory::updateOrCreate(
            ['name' => 'Homepage Hero'],
            ['status' => 1]
        );

        $banners = [
            ['link' => '/product/primal-gold', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/09/01a05bcc-f528-7951-95f1-9d3df069ff6d.webp'],
            ['link' => '/product/masala-combo', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/06/019eaae7-e684-7d5b-82ab-94aa2a4143aa.webp'],
            ['link' => '/product/ghee', 'image' => 'https://cdn.falaqfood.com/uploads/media/2026/08/019fbc18-f764-7c70-bf91-50dd67e2dcc4.webp'],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['link' => $banner['link'], 'image' => $banner['image']],
                ['category_id' => $bannerCategory->id, 'status' => 1]
            );
        }

        $pages = [
            ['name' => 'About us', 'slug' => 'about-us', 'title' => 'About Falaq Food', 'description' => '<p>Falaq Food brings pure, natural and thoughtfully sourced food to families in Bangladesh.</p>'],
            ['name' => 'Corporate Deals', 'slug' => 'corporate-deal', 'title' => 'Corporate Deals', 'description' => '<p>Talk to our team about curated food gifts and corporate orders.</p>'],
            ['name' => 'Return & Refund Policy', 'slug' => 'return-refund-policy', 'title' => 'Shipping & Returns', 'description' => '<p>We are committed to making every delivery clear, safe and dependable.</p>'],
        ];

        foreach ($pages as $page) {
            CreatePage::updateOrCreate(
                ['slug' => $page['slug']],
                [...$page, 'status' => 1]
            );
        }

        if (Schema::hasTable('blogs')) {
            Blog::updateOrCreate(
                ['slug' => 'why-choose-natural-food'],
                [
                    'title' => 'Why choose natural food for everyday wellbeing?',
                    'short_description' => 'Small ingredient choices can make everyday meals more thoughtful.',
                    'description' => '<p>Natural food is about knowing what goes into the pantry and choosing trusted sources.</p>',
                    'image' => $products[0]['image'],
                    'status' => 1,
                ]
            );
        }

        GeneralSetting::firstOrCreate(
            ['name' => 'Falaq Food'],
            [
                'white_logo' => 'https://cdn.falaqfood.com/uploads/media/2026/06/falaq-food-white.webp',
                'dark_logo' => 'https://cdn.falaqfood.com/uploads/media/2026/06/falaq-food.webp',
                'favicon' => 'https://cdn.falaqfood.com/uploads/media/2026/06/falaq-favicon.webp',
                'copyright' => 'Copyright © 2026 Falaq Food',
                'status' => 1,
            ]
        );
    }
}
