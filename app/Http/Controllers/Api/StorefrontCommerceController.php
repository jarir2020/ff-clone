<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StorefrontCommerceController extends Controller
{
    public function catalog(Request $request): JsonResponse
    {
        $query = Product::query()->where('status', 1);

        if (Schema::hasColumn('products', 'approval_status')) {
            $query->where('approval_status', 'approved');
        }

        if ($request->filled('category')) {
            $category = Category::query()->where('slug', $request->string('category'))->first();
            $query->where('category_id', $category?->id ?? -1);
        }

        if ($request->filled('search')) {
            $term = trim((string) $request->input('search'));
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('product_code', 'like', "%{$term}%");
            });
        }

        if ($request->boolean('offers')) {
            $query->whereNotNull('old_price')->whereColumn('old_price', '>', 'new_price');
        }

        if ($request->filled('min_price')) {
            $query->where('new_price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('new_price', '<=', (float) $request->input('max_price'));
        }

        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price-low' => $query->orderBy('new_price'),
            'price-high' => $query->orderByDesc('new_price'),
            'name' => $query->orderBy('name'),
            default => $query->latest('id'),
        };

        $perPage = min(max((int) $request->input('per_page', 12), 1), 48);
        $products = $query->with(['image', 'category:id,name,slug'])->paginate($perPage)->withQueryString();

        return response()->json([
            'categories' => Category::query()
                ->where('status', 1)
                ->where('parent_id', 0)
                ->orderBy('id')
                ->get(['id', 'name', 'slug'])
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])
                ->values(),
            'products' => $products->getCollection()->map(fn (Product $product) => $this->productCard($product))->values(),
            'pagination' => [
                'currentPage' => $products->currentPage(),
                'lastPage' => $products->lastPage(),
                'perPage' => $products->perPage(),
                'total' => $products->total(),
            ],
            'filters' => [
                'category' => $request->input('category'),
                'search' => $request->input('search'),
                'offers' => $request->boolean('offers'),
                'sort' => $sort,
            ],
        ]);
    }

    public function product(string $slug): JsonResponse
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->with(['images', 'image', 'category:id,name,slug', 'variantPrices.color', 'variantPrices.size', 'wholesalePrices'])
            ->firstOrFail();

        $related = Product::query()
            ->where('status', 1)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->with(['image', 'category:id,name,slug'])
            ->latest('id')
            ->limit(4)
            ->get()
            ->map(fn (Product $item) => $this->productCard($item))
            ->values();

        return response()->json([
            'product' => [
                ...$this->productCard($product),
                'description' => $product->description,
                'productCode' => $product->product_code,
                'images' => $this->productImages($product),
                'variants' => $product->variantPrices->map(fn ($variant) => [
                    'id' => $variant->id,
                    'colorId' => $variant->color_id,
                    'color' => $variant->color?->name,
                    'sizeId' => $variant->size_id,
                    'size' => $variant->size?->name ?? $variant->size?->sizeName,
                    'priceValue' => (float) $variant->price,
                    'price' => $this->money((float) $variant->price),
                    'stock' => (int) $variant->stock,
                    'images' => $this->variantImages($product, $variant->color_id, $variant->size_id),
                ])->values(),
                'wholesalePrices' => $product->wholesalePrices->map(fn ($tier) => [
                    'minQuantity' => (int) $tier->min_quantity,
                    'maxQuantity' => $tier->max_quantity === null ? null : (int) $tier->max_quantity,
                    'price' => $this->money((float) $tier->wholesale_price),
                ])->values(),
            ],
            'related' => $related,
        ]);
    }

    private function productCard(Product $product): array
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
            'priceValue' => $newPrice,
            'oldPrice' => $oldPrice > $newPrice ? $this->money($oldPrice) : null,
            'oldPriceValue' => $oldPrice > $newPrice ? $oldPrice : null,
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

    private function money(float $amount): string
    {
        return '৳ ' . number_format($amount, 0);
    }

    private function assetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $path = ltrim($path, '/');
        if (Str::startsWith($path, 'public/')) {
            $path = Str::after($path, 'public/');
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset($path);
    }
    private function productImages(Product $product): array
    {
        $default = $product->images
            ->filter(fn ($image) => !$image->color_id && !$image->size_id)
            ->map(fn ($image) => $this->assetUrl($image->image))
            ->filter()
            ->values();

        $images = $default->isNotEmpty()
            ? $default
            : $product->images->map(fn ($image) => $this->assetUrl($image->image))->filter()->values();

        return $images->all();
    }

    private function variantImages(Product $product, ?int $colorId, ?int $sizeId): array
    {
        $images = $product->images;
        $matches = fn ($image, $color, $size) =>
            (int) ($image->color_id ?? 0) === (int) ($color ?? 0)
            && (int) ($image->size_id ?? 0) === (int) ($size ?? 0);

        $candidates = [];
        if ($colorId && $sizeId) {
            $candidates[] = $images->filter(fn ($image) => $matches($image, $colorId, $sizeId));
        }
        if ($colorId) {
            $candidates[] = $images->filter(fn ($image) => $matches($image, $colorId, null));
        }
        if ($sizeId) {
            $candidates[] = $images->filter(fn ($image) => $matches($image, null, $sizeId));
        }
        $candidates[] = $images->filter(fn ($image) => $matches($image, null, null));

        foreach ($candidates as $candidate) {
            $urls = $candidate->map(fn ($image) => $this->assetUrl($image->image))->filter()->values();
            if ($urls->isNotEmpty()) {
                return $urls->all();
            }
        }

        return array_values(array_filter([$this->assetUrl(optional($product->image)->image)]));
    }
}