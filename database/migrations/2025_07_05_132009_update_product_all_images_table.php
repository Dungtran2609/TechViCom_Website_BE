<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_all_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('id');
            $table->string('image_path')->after('product_id');
            $table->integer('sort_order')->default(0)->after('image_path');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('product_all_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'image_path', 'sort_order']);
        });
    }
};
