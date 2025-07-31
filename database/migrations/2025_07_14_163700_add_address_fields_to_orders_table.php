<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->unsignedInteger('shipping_fee')->default(0)->after('address');

            $table->unsignedBigInteger('province_id')->nullable()->after('id');
            $table->unsignedBigInteger('district_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('ward_id')->nullable()->after('district_id');
            $table->string('address')->nullable()->after('ward_id');

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');
            $table->unsignedInteger('shipping_fee')->default(0)->after('address');

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->dropColumn('shipping_fee');

            $table->dropForeign(['province_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['province_id', 'district_id', 'ward_id', 'address']);
            $table->dropColumn('shipping_fee');

        });
    }
};
