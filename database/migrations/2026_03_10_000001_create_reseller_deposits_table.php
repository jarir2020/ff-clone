<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable("reseller_deposits")) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `reseller_deposits` MODIFY `user_id` INT UNSIGNED NOT NULL");
        }

        if (!Schema::hasTable("reseller_deposits")) {
            Schema::create("reseller_deposits", function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger("user_id");
                $table->decimal("amount", 14, 2);
                $table->string("payment_gateway", 50)->default("uddoktapay");
                $table->string("transaction_id")->nullable();
                $table->string("status", 20)->default("pending");
                $table->timestamps();
            });
        }

        if (Schema::getConnection()->getDriverName() === "mysql" && Schema::hasTable("users")) {
            $foreignKey = \Illuminate\Support\Facades\DB::selectOne("SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'reseller_deposits' AND CONSTRAINT_NAME = 'reseller_deposits_user_id_foreign'");
            if (!$foreignKey) {
                Schema::table("reseller_deposits", function (Blueprint $table) {
                    $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
                });
            }
        }

        if (!Schema::hasColumn("orders", "delivery_charge_deducted")) {
            Schema::table("orders", function (Blueprint $table) {
                $table->boolean("delivery_charge_deducted")->default(false);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reseller_deposits');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_charge_deducted');
        });
    }
};
