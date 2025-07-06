<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_all_images', function (Blueprint $table) {
            $table->renameColumn('variant_id', 'product_variant_id');
        });
    }

    public function down()
    {
        Schema::table('product_all_images', function (Blueprint $table) {
            $table->renameColumn('product_variant_id', 'variant_id');
        });
    }
};
