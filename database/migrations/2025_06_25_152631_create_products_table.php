<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
        $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
        $table->string('name');
        $table->string('sku')->unique();
        $table->decimal('price', 10, 2);
        $table->decimal('discount_price', 10, 2)->nullable();
        $table->decimal('weight', 10, 2);
        $table->string('dimensions');
        $table->integer('stock');
        $table->text('description');
        $table->enum('status', ['available', 'out_of_stock', 'discontinued'])->default('available');
        $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('products');
    }
}
