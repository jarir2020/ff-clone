<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_variant_prices')) {
            return;
        }

        Schema::create('product_variant_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->decimal('price', 14, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('sku')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'color_id', 'size_id'], 'variant_price_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_prices');
    }
};
