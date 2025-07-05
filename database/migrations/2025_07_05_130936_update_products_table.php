<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('sku')->nullable()->after('slug');
            $table->enum('type', ['simple', 'variable'])->default('simple')->after('sku');

            $table->renameColumn('discount_price', 'sale_price');

            $table->unsignedInteger('low_stock_amount')
                  ->nullable()
                  ->after('stock')
                  ->comment('Ngưỡng cảnh báo tồn kho');

            $table->text('short_description')->nullable()->after('low_stock_amount');
            $table->longText('long_description')->nullable()->after('short_description');

            $table->string('thumbnail')->nullable()->after('long_description');

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'sku',
                'type',
                'low_stock_amount',
                'short_description',
                'long_description',
                'thumbnail'
            ]);

            $table->renameColumn('sale_price', 'discount_price');

            $table->dropSoftDeletes();
        });
    }
};
