<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Fraud
            if (!Schema::hasColumn("orders", "fraud_success")) {
            $table->integer('fraud_success')->default(0)->after('order_status');
            }
            if (!Schema::hasColumn("orders", "fraud_cancel")) {
            $table->integer('fraud_cancel')->default(0)->after('fraud_success');
            }
            if (!Schema::hasColumn("orders", "fraud_rate")) {
            $table->decimal('fraud_rate', 5, 2)->default(0)->after('fraud_cancel');
            }

            // Pathao
            if (!Schema::hasColumn("orders", "pathao_success")) {
            $table->integer('pathao_success')->default(0)->after('fraud_rate');
            }
            if (!Schema::hasColumn("orders", "pathao_cancel")) {
            $table->integer('pathao_cancel')->default(0)->after('pathao_success');
            }
            if (!Schema::hasColumn("orders", "pathao_rate")) {
            $table->decimal('pathao_rate', 5, 2)->default(0)->after('pathao_cancel');
            }

            // RedX
            if (!Schema::hasColumn("orders", "redx_success")) {
            $table->integer('redx_success')->default(0)->after('pathao_rate');
            }
            if (!Schema::hasColumn("orders", "redx_cancel")) {
            $table->integer('redx_cancel')->default(0)->after('redx_success');
            }
            if (!Schema::hasColumn("orders", "redx_rate")) {
            $table->decimal('redx_rate', 5, 2)->default(0)->after('redx_cancel');
            }

            // Steadfast
            if (!Schema::hasColumn("orders", "steadfast_success")) {
            $table->integer('steadfast_success')->default(0)->after('redx_rate');
            }
            if (!Schema::hasColumn("orders", "steadfast_cancel")) {
            $table->integer('steadfast_cancel')->default(0)->after('steadfast_success');
            }
            if (!Schema::hasColumn("orders", "steadfast_rate")) {
            $table->decimal('steadfast_rate', 5, 2)->default(0)->after('steadfast_cancel');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'fraud_success','fraud_cancel','fraud_rate',
                'pathao_success','pathao_cancel','pathao_rate',
                'redx_success','redx_cancel','redx_rate',
                'steadfast_success','steadfast_cancel','steadfast_rate'
            ]);
        });
    }
};
