<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->integer('estimated_days');
            $table->decimal('max_weight', 10, 2);
            $table->text('regions');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_methods');
    }
}
