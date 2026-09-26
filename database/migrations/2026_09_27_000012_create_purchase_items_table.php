<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchase_items')) {
            return;
        }

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_price_id')->nullable();
            $table->unsignedInteger('qty')->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->unsignedInteger('returned_qty')->default(0);
            $table->timestamps();

            $table->index('purchase_id');
            $table->index('product_id');
            $table->index('variant_price_id');
            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
