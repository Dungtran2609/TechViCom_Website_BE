<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm các foreignId
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->after('user_id')->constrained('user_addresses')->onDelete('cascade');

            // Thêm các cột khác
            $table->string('payment_method')->after('address_id');
            $table->string('status')->after('payment_method');
            $table->decimal('total_amount', 10, 2)->nullable();
            // if (! Schema::hasColumn('orders', 'total_amount')) {
            // $table->decimal('total_amount', 15, 2)->default(0)->after('final_total');
            // } else {
            // $table->decimal('total_amount', 15, 2)->default(0)->change();
            // }
            $table->decimal('final_total', 10, 2)->nullable()->after('total_amount');

            $table->string('recipient_name')->after('final_total');
            $table->string('recipient_phone')->after('recipient_name');
            $table->text('recipient_address')->after('recipient_phone');

            $table->dateTime('shipped_at')->nullable()->after('status');

            // Thêm softDeletes & timestamps nếu chưa có
            $table->softDeletes()->after('shipped_at');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop foreign keys + columns
            $table->dropForeign(['user_id']);
            $table->dropForeign(['address_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('address_id');

            $table->dropColumn('payment_method');
            $table->dropColumn('status');
            $table->dropColumn('total_amount');
            $table->dropColumn('final_total');

            $table->dropColumn('recipient_name');
            $table->dropColumn('recipient_phone');
            $table->dropColumn('recipient_address');

            $table->dropColumn('shipped_at');
            $table->dropSoftDeletes();
            // $table->dropTimestamps();
        });
    }
};
