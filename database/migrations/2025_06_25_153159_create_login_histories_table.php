<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->string('device_info');
            $table->enum('login_status', ['success', 'failed']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
}
