<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The imported admin/frontend code targets a richer product schema than
     * the original Ecommerce5 products migration. Add the missing fields in
     * an idempotent way so existing databases can be upgraded safely.
     */
    public function up(): void
    {
        $columns = [
            'subcategory_id'       => fn (Blueprint $table) => $table->integer('subcategory_id')->nullable(),
            'childcategory_id'     => fn (Blueprint $table) => $table->integer('childcategory_id')->nullable(),
            'product_type'         => fn (Blueprint $table) => $table->string('product_type', 20)->default('physical'),
            'meta_title'           => fn (Blueprint $table) => $table->string('meta_title')->nullable(),
            'meta_keywords'        => fn (Blueprint $table) => $table->string('meta_keywords')->nullable(),
            'meta_image'           => fn (Blueprint $table) => $table->string('meta_image')->nullable(),
            'advance_amount'       => fn (Blueprint $table) => $table->decimal('advance_amount', 14, 2)->default(0),
            'is_digital'           => fn (Blueprint $table) => $table->tinyInteger('is_digital')->default(0),
            'digital_file'         => fn (Blueprint $table) => $table->string('digital_file')->nullable(),
            'download_limit'       => fn (Blueprint $table) => $table->integer('download_limit')->nullable(),
            'download_expire_days' => fn (Blueprint $table) => $table->integer('download_expire_days')->nullable(),
            'pro_video'            => fn (Blueprint $table) => $table->string('pro_video')->nullable(),
            'sold'                 => fn (Blueprint $table) => $table->unsignedInteger('sold')->default(0),
            'flashsale'            => fn (Blueprint $table) => $table->tinyInteger('flashsale')->default(0),
        ];

        foreach ($columns as $name => $definition) {
            if (! Schema::hasColumn('products', $name)) {
                Schema::table('products', $definition);
            }
        }
    }

    public function down(): void
    {
        $columns = [
            'subcategory_id',
            'childcategory_id',
            'product_type',
            'meta_title',
            'meta_keywords',
            'meta_image',
            'advance_amount',
            'is_digital',
            'digital_file',
            'download_limit',
            'download_expire_days',
            'pro_video',
            'sold',
            'flashsale',
        ];

        foreach ($columns as $name) {
            if (Schema::hasColumn('products', $name)) {
                Schema::table('products', fn (Blueprint $table) => $table->dropColumn($name));
            }
        }
    }
};
