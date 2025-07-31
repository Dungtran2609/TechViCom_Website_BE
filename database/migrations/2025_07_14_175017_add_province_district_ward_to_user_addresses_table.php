<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->after('id');
            $table->unsignedBigInteger('district_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('ward_id')->nullable()->after('district_id');

            $table->foreign('province_id')->references('id')->on('provinces')->nullOnDelete();
            $table->foreign('district_id')->references('id')->on('districts')->nullOnDelete();
            $table->foreign('ward_id')->references('id')->on('wards')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['province_id', 'district_id', 'ward_id']);
        });
    }
};
