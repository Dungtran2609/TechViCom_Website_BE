<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('config_key');
            $table->text('config_value');
            $table->timestamps(); // Tạo cả hai cột created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
