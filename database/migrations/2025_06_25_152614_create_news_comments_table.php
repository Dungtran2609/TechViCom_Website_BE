<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại đến bảng users (Laravel sẽ mặc định dùng users.id)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Khóa ngoại đến bảng news (phải dùng news_id vì bảng news không có id)
            $table->unsignedBigInteger('news_id');
            $table->foreign('news_id')->references('news_id')->on('news')->onDelete('cascade');

            $table->text('content');
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_comments');
    }
}
