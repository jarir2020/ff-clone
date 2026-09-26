<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('courierapis') || Schema::hasColumn('courierapis', 'status')) {
            return;
        }

        Schema::table('courierapis', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->after('type');
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('courierapis') || ! Schema::hasColumn('courierapis', 'status')) {
            return;
        }

        Schema::table('courierapis', function (Blueprint $table) {
            $table->dropIndex(['type', 'status']);
            $table->dropColumn('status');
        });
    }
};
