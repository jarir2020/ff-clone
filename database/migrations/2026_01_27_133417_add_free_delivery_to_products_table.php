<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $column = $table->tinyInteger("free_delivery")->default(0)->comment("0=No, 1=Yes");
            if (Schema::hasColumn("products", "is_digital")) {
                $column->after("is_digital");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('free_delivery');
        });
    }
};
