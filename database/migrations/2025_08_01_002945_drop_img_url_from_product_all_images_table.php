<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_all_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_all_images', 'img_url')) {
                $table->dropColumn('img_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_all_images', function (Blueprint $table) {
            $table->string('img_url')->nullable();
        });
    }
};
