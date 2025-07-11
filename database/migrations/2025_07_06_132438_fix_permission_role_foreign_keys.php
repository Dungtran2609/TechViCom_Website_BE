<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('permission_role', function (Blueprint $table) {
            // Nếu có foreign key thì mới drop để tránh lỗi
            if (Schema::hasColumn('permission_role', 'role_id')) {
                try {
                    $table->dropForeign(['role_id']);
                } catch (\Throwable $e) {}
            }

            if (Schema::hasColumn('permission_role', 'permission_id')) {
                try {
                    $table->dropForeign(['permission_id']);
                } catch (\Throwable $e) {}
            }

            // Thêm lại khóa ngoại đúng theo bảng roles và permissions với khóa chính là id
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['permission_id']);
        });
    }
};
