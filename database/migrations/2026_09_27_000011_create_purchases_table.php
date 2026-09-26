<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchases')) {
            return;
        }

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->string('invoice_no', 50);
            $table->date('purchase_date');
            $table->unsignedInteger('total_qty')->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('status', 30)->default('completed');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('supplier_id');
            $table->index('purchase_date');
            $table->index('invoice_no');
            $table->index('status');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
