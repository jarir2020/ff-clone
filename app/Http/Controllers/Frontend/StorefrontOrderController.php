<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Cart;

class StorefrontOrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:155'],
            'phone' => ['required', 'string', 'max:55'],
            'address' => ['required', 'string', 'max:500'],
            'area' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cod'],
        ]);

        $cartItems = Cart::instance('shopping')->content();
        if ($cartItems->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }

        $order = DB::transaction(function () use ($data, $cartItems) {
            $customer = Customer::where('phone', $data['phone'])->first();
            if (!$customer) {
                $customer = Customer::create([
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(5)),
                    'phone' => $data['phone'],
                    'password' => bcrypt(Str::random(16)),
                    'verify' => 1,
                    'status' => 'active',
                ]);
            } else {
                $customer->update(['name' => $data['name']]);
            }

            $subtotal = 0.0;
            $lines = [];
            foreach ($cartItems as $cartItem) {
                $product = Product::with('wholesalePrices')->lockForUpdate()->find($cartItem->id);
                if (!$product || (int) $product->status !== 1) {
                    throw ValidationException::withMessages(['cart' => 'A product in your cart is no longer available.']);
                }

                $quantity = (int) $cartItem->qty;
                if ((int) $product->stock < $quantity) {
                    throw ValidationException::withMessages(['cart' => "{$product->name} does not have enough stock."]);
                }

                $price = $product->resolveSalePrice(
                    $quantity,
                    $cartItem->options->color_id ?? null,
                    $cartItem->options->size_id ?? null
                );
                $price = $price > 0 ? $price : (float) $cartItem->price;
                $subtotal += $price * $quantity;
                $lines[] = compact('product', 'quantity', 'price', 'cartItem');
            }

            $invoice = $this->invoice();
            $order = Order::create([
                'invoice_id' => $invoice,
                'amount' => (int) round($subtotal),
                'discount' => 0,
                'shipping_charge' => 0,
                'customer_id' => $customer->id,
                'order_status' => 1,
            ]);

            Shipping::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'area' => $data['area'] ?? '',
            ]);

            Payment::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'amount' => (int) round($subtotal),
                'payment_method' => 'cod',
                'payment_status' => 'pending',
            ]);

            foreach ($lines as $line) {
                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'purchase_price' => (int) round((float) ($line['product']->purchase_price ?? 0)),
                    'sale_price' => (int) round($line['price']),
                    'qty' => $line['quantity'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
            }

            return $order;
        });

        Cart::instance('shopping')->destroy();

        return response()->json([
            'message' => 'Your order has been placed successfully.',
            'orderId' => $order->id,
            'invoiceId' => $order->invoice_id,
            'redirect' => '/customer/order-success/' . $order->id,
        ], 201);
    }

    private function invoice(): string
    {
        do {
            $invoice = (string) random_int(10000, 99999);
        } while (Order::where('invoice_id', $invoice)->exists());

        return $invoice;
    }
}
