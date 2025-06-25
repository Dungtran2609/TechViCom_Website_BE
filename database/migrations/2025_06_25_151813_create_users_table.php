<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('phone_number', 20)->nullable();
            $table->string('image_profile')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Non-Binary', 'Other'])->default('Male');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
