<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->string('name');
            $table->text('description')->nullable();
            // $table->decimal('fee', 10, 2)->nullable();
;
            $table->integer('estimated_days');
            $table->decimal('max_weight', 10, 2);
            $table->text('regions');
        });
    }

    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'description',
                
                'estimated_days',
                'max_weight',
                'regions',
            ]);
        });
    }
};
