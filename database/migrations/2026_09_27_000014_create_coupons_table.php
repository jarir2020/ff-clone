<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('coupons')) {
            return;
        }

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('type', 20)->default('flat');
            $table->decimal('value', 15, 2);
            $table->decimal('min_purchase', 15, 2)->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();

            $table->index('status');
            $table->index(['valid_from', 'valid_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
