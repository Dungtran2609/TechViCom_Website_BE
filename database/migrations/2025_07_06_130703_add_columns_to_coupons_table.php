<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('code');
            $table->string('discount_type');
            $table->decimal('value', 10, 2);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->decimal('min_order_value', 10, 2)->nullable();
            $table->decimal('max_order_value', 10, 2)->nullable();
            $table->integer('max_usage_per_user');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('status')->default(true);
        });
    }

    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'code',
                'discount_type',
                'value',
                'max_discount_amount',
                'min_order_value',
                'max_order_value',
                'max_usage_per_user',
                'start_date',
                'end_date',
                'status',
            ]);
        });
    }
};
