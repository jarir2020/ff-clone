<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable("reseller_wallet_transactions")) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `reseller_wallet_transactions` MODIFY `user_id` INT UNSIGNED NOT NULL");

            if (Schema::getConnection()->getDriverName() === "mysql" && Schema::hasTable("users")) {
                $foreignKey = \Illuminate\Support\Facades\DB::selectOne("SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'reseller_wallet_transactions' AND CONSTRAINT_NAME = 'reseller_wallet_transactions_user_id_foreign'");
                if (!$foreignKey) {
                    Schema::table("reseller_wallet_transactions", function (Blueprint $table) {
                        $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
                    });
                }
            }

            return;
        }

        Schema::create("reseller_wallet_transactions", function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("user_id");
            $table->string("type", 30);
            $table->decimal("amount", 14, 2);
            $table->decimal("balance_after", 14, 2)->nullable();
            $table->string("reference_type", 50)->nullable();
            $table->unsignedBigInteger("reference_id")->nullable();
            $table->string("description")->nullable();
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseller_wallet_transactions');
    }
};
