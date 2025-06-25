<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportUserActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('report_user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('activity_date');
            $table->integer('login_count')->default(0);
            $table->integer('order_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('page_views')->default(0);
            $table->integer('time_spent')->default(0);
            $table->integer('interactions')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_user_activities');
    }
}
