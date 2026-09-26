<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("reseller_withdrawals")) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `reseller_withdrawals` MODIFY `user_id` INT UNSIGNED NOT NULL");

            $foreignKey = \Illuminate\Support\Facades\DB::selectOne("SELECT CONSTRAINT_NAME FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'reseller_withdrawals' AND CONSTRAINT_NAME = 'reseller_withdrawals_user_id_foreign'");
            if (!$foreignKey) {
                Schema::table("reseller_withdrawals", function (Blueprint $table) {
                    $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
                });
            }

            return;
        }

        Schema::create('reseller_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // reseller user_id
            $table->decimal('amount', 14, 2);
            $table->decimal('charge', 14, 2)->default(0);
            $table->string('payout_method')->default('manual'); // manual|bkash|nagad|bank
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->text('admin_note')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseller_withdrawals');
    }
};
