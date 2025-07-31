<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('permission_role', function (Blueprint $table) {
            // Nếu đã tồn tại khóa ngoại cũ, bạn cần drop nó trước
            $table->dropForeign(['role_id']);
            $table->dropForeign(['permission_id']);

            // Tạo lại foreign key đúng với bảng roles và permissions sử dụng khóa chính là id
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
