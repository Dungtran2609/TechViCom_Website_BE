<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable(); // Có thể để nullable nếu bạn muốn cho phép bỏ qua
            $table->enum('type', ['simple', 'variable'])->default('simple');

            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable(); // Gộp discount_price -> sale_price luôn
            $table->integer('stock')->nullable();
            $table->unsignedInteger('low_stock_amount')->nullable()->comment('Ngưỡng cảnh báo tồn kho');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();

            $table->string('thumbnail')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
