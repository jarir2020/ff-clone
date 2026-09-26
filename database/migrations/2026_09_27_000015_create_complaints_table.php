<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('complaints')) {
            return;
        }

        Schema::create('complaints', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('order_id', 50)->nullable();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('image')->nullable();
            $table->text('description');
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index('customer_id');
            $table->index('order_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
