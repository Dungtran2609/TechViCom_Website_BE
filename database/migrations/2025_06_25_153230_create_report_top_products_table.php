<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTopProductsTable extends Migration
{
    public function up()
    {
        Schema::create('report_top_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('sold_quantity');
            $table->date('report_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_top_products');
    }
}
