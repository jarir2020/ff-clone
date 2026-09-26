<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariantPrice;
use App\Models\Size;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cart;

class StorefrontCartController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json($this->payload());
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'color_id' => ['nullable', 'integer'],
            'size_id' => ['nullable', 'integer'],
        ]);

        $product = Product::with(['image', 'wholesalePrices'])->where('status', 1)->findOrFail($data['product_id']);
        $quantity = (int) ($data['quantity'] ?? 1);
        $colorId = isset($data['color_id']) ? (int) $data['color_id'] : null;
        $sizeId = isset($data['size_id']) ? (int) $data['size_id'] : null;

        $existing = Cart::instance('shopping')->content()->first(function ($item) use ($product, $colorId, $sizeId) {
            return (int) $item->id === (int) $product->id
                && (string) ($item->options->color_id ?? '') === (string) ($colorId ?? '')
                && (string) ($item->options->size_id ?? '') === (string) ($sizeId ?? '');
        });

        $nextQuantity = $quantity + ($existing ? (int) $existing->qty : 0);
        if ((int) $product->stock < $nextQuantity) {
            return response()->json(['message' => 'This product does not have enough stock.'], 422);
        }

        $price = $product->resolveSalePrice($nextQuantity, $colorId, $sizeId);
        if ($price <= 0) {
            $price = (float) ($product->new_price ?? $product->old_price ?? 0);
        }

        $variant = ProductVariantPrice::query()
            ->where('product_id', $product->id)
            ->when($colorId, fn ($query) => $query->where('color_id', $colorId), fn ($query) => $query->whereNull('color_id'))
            ->when($sizeId, fn ($query) => $query->where('size_id', $sizeId), fn ($query) => $query->whereNull('size_id'))
            ->first();

        $options = [
            'image' => optional($product->image)->image,
            'slug' => $product->slug,
            'color_id' => $colorId,
            'size_id' => $sizeId,
            'product_color' => $colorId ? (Color::find($colorId)?->name) : null,
            'product_size' => $sizeId ? (Size::find($sizeId)?->name ?? Size::find($sizeId)?->sizeName) : null,
            'variant_price_id' => $variant?->id,
            'purchase_price' => (float) ($product->purchase_price ?? 0),
            'is_digital' => (int) ($product->is_digital ?? 0),
            'free_delivery' => (int) ($product->free_delivery ?? 0),
        ];

        if ($existing) {
            Cart::instance('shopping')->update($existing->rowId, [
                'qty' => $nextQuantity,
                'price' => $price,
                'options' => $options,
            ]);
        } else {
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $quantity,
                'price' => $price,
                'options' => $options,
            ]);
        }

        return response()->json($this->payload(), 201);
    }

    public function update(Request $request, string $rowId): JsonResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        $item = Cart::instance('shopping')->get($rowId);

        if (!$item) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        $product = Product::with('wholesalePrices')->findOrFail($item->id);
        if ((int) $product->stock < (int) $data['quantity']) {
            return response()->json(['message' => 'This product does not have enough stock.'], 422);
        }

        $price = $product->resolveSalePrice((int) $data['quantity'], $item->options->color_id ?? null, $item->options->size_id ?? null);
        Cart::instance('shopping')->update($rowId, [
            'qty' => (int) $data['quantity'],
            'price' => $price > 0 ? $price : $item->price,
        ]);

        return response()->json($this->payload());
    }

    public function remove(string $rowId): JsonResponse
    {
        Cart::instance('shopping')->update($rowId, 0);
        return response()->json($this->payload());
    }

    public function clear(): JsonResponse
    {
        Cart::instance('shopping')->destroy();
        return response()->json($this->payload());
    }

    private function payload(): array
    {
        $items = Cart::instance('shopping')->content()->map(function ($item) {
            return [
                'rowId' => $item->rowId,
                'productId' => (int) $item->id,
                'name' => $item->name,
                'slug' => $item->options->slug ?? null,
                'image' => $this->assetUrl($item->options->image ?? null),
                'quantity' => (int) $item->qty,
                'price' => (float) $item->price,
                'priceFormatted' => '৳ ' . number_format((float) $item->price, 0),
                'subtotal' => (float) $item->subtotal,
                'subtotalFormatted' => '৳ ' . number_format((float) $item->subtotal, 0),
                'color' => $item->options->product_color ?? null,
                'size' => $item->options->product_size ?? null,
            ];
        })->values();

        $subtotal = (float) $items->sum('subtotal');

        return [
            'items' => $items,
            'count' => (int) $items->sum('quantity'),
            'subtotal' => $subtotal,
            'subtotalFormatted' => '৳ ' . number_format($subtotal, 0),
        ];
    }

    private function assetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : asset(ltrim($path, '/'));
    }
}
