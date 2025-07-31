<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->foreignId('shipping_method_id')
                    ->nullable()
                    ->constrained('shipping_methods')
                    ->nullOnDelete()
                    ->after('address_id');
            }

            if (! Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')
                    ->nullable()
                    ->constrained('coupons')
                    ->nullOnDelete()
                    ->after('shipping_method_id');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->dropForeign(['shipping_method_id']);
                $table->dropColumn('shipping_method_id');
            }

            if (Schema::hasColumn('orders', 'coupon_id')) {
                $table->dropForeign(['coupon_id']);
                $table->dropColumn('coupon_id');
            }
        });
    }
};
