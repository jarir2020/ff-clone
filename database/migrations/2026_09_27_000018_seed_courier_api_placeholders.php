<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('courierapis')) {
            return;
        }

        if (! Schema::hasColumn('courierapis', 'status')) {
            Schema::table('courierapis', function (Blueprint $table): void {
                $table->unsignedTinyInteger('status')->default(0)->after('type');
            });
        }

        $now = now();
        $defaults = [
            'steadfast' => [
                'url' => 'https://portal.packzy.com/api/v1',
            ],
            'pathao' => [
                'url' => 'https://api-hermes.pathao.com',
            ],
        ];

        foreach ($defaults as $type => $config) {
            if (DB::table('courierapis')->where('type', $type)->exists()) {
                continue;
            }

            DB::table('courierapis')->insert([
                'type' => $type,
                'status' => 0,
                'api_key' => null,
                'secret_key' => null,
                'client_id' => null,
                'client_secret' => null,
                'username' => null,
                'password' => null,
                'url' => $config['url'],
                'token' => null,
                'webhook_url' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Keep courier configuration intact if this migration is rolled back.
    }
};
