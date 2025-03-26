<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up()
{
Schema::create('roles', function (Blueprint $table) {
$table->id(); // Tự động tạo khóa chính (bigint, auto-increment)
$table->string('name')->unique(); // Tên role (admin, user, editor,...)
$table->timestamps(); // created_at & updated_at
});
}

public function down()
{
Schema::dropIfExists('roles');
}
};
