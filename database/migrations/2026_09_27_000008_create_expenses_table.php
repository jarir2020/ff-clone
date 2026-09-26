<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('expenses')) {
            return;
        }

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->string('category', 100)->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('fund_transaction_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('expense_date');
            $table->index('fund_transaction_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->foreign('fund_transaction_id')
                ->references('id')
                ->on('fund_transactions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
