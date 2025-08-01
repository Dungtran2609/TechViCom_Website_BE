<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories', 'id');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->string('image')->nullable();      
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();                    
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
