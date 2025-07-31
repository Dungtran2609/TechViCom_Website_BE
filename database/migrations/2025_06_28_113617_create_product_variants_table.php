<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    public function up()
{
    Schema::create('product_variants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->string('sku')->unique();
        $table->decimal('price', 10, 2);
        $table->decimal('sale_price', 10, 2)->nullable();
        $table->decimal('weight', 10, 2)->nullable(); // cho phép null
        $table->string('dimensions')->nullable();     // cho phép null
        $table->integer('stock');
        $table->string('image')->nullable();
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}

