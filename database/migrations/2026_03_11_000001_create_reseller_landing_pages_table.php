<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable("reseller_landing_pages")) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `reseller_landing_pages` MODIFY `user_id` INT UNSIGNED NOT NULL");

            if (Schema::getConnection()->getDriverName() === "mysql" && Schema::hasTable("users")) {
                $foreignKey = \Illuminate\Support\Facades\DB::selectOne("SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'reseller_landing_pages' AND CONSTRAINT_NAME = 'reseller_landing_pages_user_id_foreign'");
                if (!$foreignKey) {
                    Schema::table("reseller_landing_pages", function (Blueprint $table) {
                        $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
                    });
                }
            }

            return;
        }

        Schema::create("reseller_landing_pages", function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("user_id");
            $table->string("slug", 100)->unique();
            $table->string("custom_domain", 255)->nullable();
            $table->string("logo")->nullable();
            $table->string("title")->nullable();
            $table->string("tagline")->nullable();
            $table->json("slider_images")->nullable();
            $table->string("banner_image")->nullable();
            $table->string("phone", 50)->nullable();
            $table->string("email", 100)->nullable();
            $table->text("address")->nullable();
            $table->boolean("is_active")->default(1);
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->index("slug");
            $table->index("user_id");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseller_landing_pages');
    }
};
