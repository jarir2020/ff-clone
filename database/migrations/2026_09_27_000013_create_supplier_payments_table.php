<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('supplier_payments')) {
            return;
        }

        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('purchase_id');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('method', 50)->default('fund');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('fund_transaction_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('supplier_id');
            $table->index('purchase_id');
            $table->index('fund_transaction_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
            $table->foreign('fund_transaction_id')->references('id')->on('fund_transactions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
