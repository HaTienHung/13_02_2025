<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Đổi kiểu dữ liệu ENUM
        DB::statement("ALTER TABLE inventory_transactions CHANGE COLUMN type type ENUM('import', 'export') NOT NULL");
    }

    public function down()
    {
        // Quay lại giá trị ENUM cũ nếu rollback
        DB::statement("ALTER TABLE inventory_transactions CHANGE COLUMN type type ENUM('increase', 'decrease') NOT NULL");
    }
};
