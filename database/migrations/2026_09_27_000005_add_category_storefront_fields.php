<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categories', 'icon')) {
            Schema::table('categories', fn (Blueprint $table) => $table->string('icon')->nullable());
        }

        if (! Schema::hasColumn('categories', 'front_view')) {
            Schema::table('categories', fn (Blueprint $table) => $table->tinyInteger('front_view')->default(0));
        }
    }

    public function down(): void
    {
        foreach (['icon', 'front_view'] as $column) {
            if (Schema::hasColumn('categories', $column)) {
                Schema::table('categories', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }
};
