<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('banner_categories')->where('id', 13)->exists();

        if (!$exists) {
            DB::table('banner_categories')->insert([
                'id'         => 13,
                'name'       => 'স্লাইডার নিচের ব্যানার (Carousel, 400x220px)',
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('banner_categories')->where('id', 13)->delete();
        DB::table('banners')->where('category_id', 13)->delete();
    }
};
