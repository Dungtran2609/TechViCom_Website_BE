<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Nếu cột description tồn tại thì drop nó
    if (Schema::hasColumn('products', 'description')) {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }

    // Thêm các field mới (nếu chưa có)
    Schema::table('products', function (Blueprint $table) {
        if (!Schema::hasColumn('products', 'is_featured')) {
            $table->boolean('is_featured')->default(false)->after('status');
        }
        if (!Schema::hasColumn('products', 'view_count')) {
            $table->unsignedInteger('view_count')->default(0)->after('is_featured');
        }
    });
}


    public function down(): void
{
    // Thêm lại description nếu chưa có
    if (!Schema::hasColumn('products', 'description')) {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });
    }

    // Drop các cột nếu tồn tại
    Schema::table('products', function (Blueprint $table) {
        $cols = [];
        if (Schema::hasColumn('products', 'is_featured')) {
            $cols[] = 'is_featured';
        }
        if (Schema::hasColumn('products', 'view_count')) {
            $cols[] = 'view_count';
        }
        if (count($cols)) {
            $table->dropColumn($cols);
        }
    });
}

};