<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fund_transactions')) {
            return;
        }

        Schema::create('fund_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('direction', ['in', 'out']);
            $table->string('source', 80);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('direction');
            $table->index(['source', 'source_id']);
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_transactions');
    }
};
