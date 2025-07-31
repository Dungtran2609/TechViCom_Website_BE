<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');             // Tên tỉnh/thành
            $table->string('code')->nullable(); // Mã code để map API ViettelPost
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
