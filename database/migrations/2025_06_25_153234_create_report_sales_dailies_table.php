<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportSalesDailiesTable extends Migration
{
    public function up()
    {
        Schema::create('report_sales_dailies', function (Blueprint $table) { 
            $table->id();
            $table->date('report_date');
            $table->integer('total_orders');
            $table->decimal('total_revenue', 15, 2);
            $table->decimal('total_refunds', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_sales_dailies'); 
    }
}
