<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payment_gateways')) {
            return;
        }

        if (! Schema::hasColumn('payment_gateways', 'status')) {
            Schema::table('payment_gateways', function (Blueprint $table): void {
                $table->unsignedTinyInteger('status')->default(0)->after('prefix');
            });
        }

        $now = now();

        foreach (['bkash', 'shurjopay', 'uddoktapay'] as $type) {
            if (DB::table('payment_gateways')->where('type', $type)->exists()) {
                continue;
            }

            DB::table('payment_gateways')->insert([
                'type' => $type,
                'app_key' => null,
                'app_secret' => null,
                'username' => null,
                'password' => null,
                'base_url' => null,
                'success_url' => null,
                'return_url' => null,
                'prefix' => null,
                'status' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Keep configured gateway records intact if this migration is rolled back.
    }
};
