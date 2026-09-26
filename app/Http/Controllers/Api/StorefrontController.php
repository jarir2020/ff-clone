<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Contact;
use App\Models\CreatePage;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Blog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    public function home(): JsonResponse
    {
        $categories = Category::query()
            ->where('status', 1)
            ->where('parent_id', 0)
            ->orderBy('id')
            ->get(['id', 'name', 'slug', 'image'])
            ->map(fn (Category $category) => $this->categoryPayload($category))
            ->values();

        $products = Product::query()
            ->where('status', 1)
            ->where('feature_product', 1)
            ->with(['image', 'category:id,name,slug'])
            ->latest('id')
            ->limit(24)
            ->get()
            ->map(fn (Product $product) => $this->productPayload($product))
            ->values();

        if ($products->isEmpty()) {
            $products = Product::query()
                ->where('status', 1)
                ->with(['image', 'category:id,name,slug'])
                ->latest('id')
                ->limit(24)
                ->get()
                ->map(fn (Product $product) => $this->productPayload($product))
                ->values();
        }

        $banners = Banner::query()
            ->where('status', 1)
            ->latest('id')
            ->limit(12)
            ->get(['id', 'category_id', 'link', 'image'])
            ->map(fn (Banner $banner) => [
                'id' => $banner->id,
                'link' => $banner->link,
                'image' => $this->assetUrl($banner->image),
                'categoryId' => $banner->category_id,
            ])
            ->values();

        return response()->json([
            'categories' => $categories,
            'products' => $products,
            'banners' => $banners,
            'settings' => $this->settingsPayload(),
            'contact' => $this->contactPayload(),
            'blogs' => $this->blogPayload(3),
        ]);
    }

    public function content(string $slug): JsonResponse
    {
        $page = CreatePage::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (!$page) {
            abort(404, 'Content page not found.');
        }

        return response()->json([
            'type' => 'page',
            'slug' => $page->slug,
            'name' => $page->name,
            'title' => $page->title,
            'description' => $page->description,
        ]);
    }

    public function contact(): JsonResponse
    {
        return response()->json([
            'type' => 'contact',
            'contact' => $this->contactPayload(),
            'settings' => $this->settingsPayload(),
        ]);
    }

    public function blogs(): JsonResponse
    {
        return response()->json([
            'items' => $this->blogPayload(12),
        ]);
    }

    public function blog(string $slug): JsonResponse
    {
        if (!Schema::hasTable('blogs')) {
            abort(404, 'Blog not found.');
        }

        $blog = Blog::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return response()->json([
            'id' => $blog->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'shortDescription' => $blog->short_description,
            'description' => $blog->description,
            'image' => $this->assetUrl($blog->image),
            'views' => (int) $blog->views,
            'createdAt' => optional($blog->created_at)->toISOString(),
        ]);
    }

    private function categoryPayload(Category $category): array
    {
        $iconMap = [
            'honey' => '🍯',
            'dates-খেজুর' => '🌴',
            'dry-food' => '🥜',
            'masala-মসলা-কম্বো' => '🌶️',
            'mix-food' => '🥣',
            'nuts-seeds' => '🌰',
            'tea' => '🍵',
        ];

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'icon' => $iconMap[$category->slug] ?? '🌿',
            'image' => $this->assetUrl($category->image),
            'href' => '/category/' . $category->slug,
        ];
    }

    private function productPayload(Product $product): array
    {
        $newPrice = (float) ($product->new_price ?? 0);
        $oldPrice = (float) ($product->old_price ?? 0);
        $discount = $oldPrice > $newPrice && $oldPrice > 0
            ? '-' . round((($oldPrice - $newPrice) / $oldPrice) * 100) . '%'
            : null;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $this->money($newPrice),
            'oldPrice' => $oldPrice > $newPrice ? $this->money($oldPrice) : null,
            'priceValue' => $newPrice,
            'oldPriceValue' => $oldPrice ?: null,
            'badge' => $discount,
            'stock' => (int) ($product->stock ?? 0),
            'image' => $this->assetUrl(optional($product->image)->image),
            'category' => $product->category ? [
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
            'href' => '/product/' . $product->slug,
        ];
    }

    private function blogPayload(int $limit): array
    {
        if (!Schema::hasTable('blogs')) {
            return [];
        }

        return Blog::query()
            ->where('status', 1)
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (Blog $blog) => [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'shortDescription' => $blog->short_description,
                'image' => $this->assetUrl($blog->image),
                'createdAt' => optional($blog->created_at)->toISOString(),
            ])
            ->values()
            ->all();
    }

    private function settingsPayload(): array
    {
        $settings = GeneralSetting::query()->first();

        return $settings ? [
            'name' => $settings->name,
            'copyright' => $settings->copyright,
            'logo' => $this->assetUrl($settings->dark_logo),
            'favicon' => $this->assetUrl($settings->favicon),
        ] : [];
    }

    private function contactPayload(): array
    {
        $contact = Contact::query()->first();

        return $contact ? $contact->toArray() : [];
    }

    private function money(float $amount): string
    {
        return '৳ ' . number_format($amount, 0);
    }

    private function assetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset(ltrim($path, '/'));
    }
}
