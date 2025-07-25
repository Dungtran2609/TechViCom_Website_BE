<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')
            ->constrained('attributes')
            ->onDelete('cascade');
            $table->string('value');
            $table->string('color_code')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }
   
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};


