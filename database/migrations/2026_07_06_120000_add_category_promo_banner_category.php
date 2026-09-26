<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('banner_categories')->where('id', 12)->exists();

        if (!$exists) {
            DB::table('banner_categories')->insert([
                'id'         => 12,
                'name'       => 'শীর্ষ ক্যাটাগরি ব্যানার (৪টি, 300x300px)',
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('banner_categories')->where('id', 12)->delete();
        DB::table('banners')->where('category_id', 12)->delete();
    }
};
