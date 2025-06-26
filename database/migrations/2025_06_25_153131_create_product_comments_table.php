<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_comments');
    }
}
