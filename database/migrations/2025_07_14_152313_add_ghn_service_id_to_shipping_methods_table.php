<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->integer('ghn_service_id')->nullable()->after('regions')
                ->comment('ID dịch vụ GHN để gọi API');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn('ghn_service_id');
        });
    }
};
