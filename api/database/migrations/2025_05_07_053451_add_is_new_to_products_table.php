<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm cột is_new
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_new')->default(false)->after('featured');
        });

        // Thêm cột active và chuyển dữ liệu từ status
        if (Schema::hasColumn('products', 'status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('active')->default(true)->after('is_new');
            });

            DB::statement("UPDATE products SET active = CASE WHEN status = 'active' THEN 1 ELSE 0 END");

            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Thêm lại cột status
        if (!Schema::hasColumn('products', 'status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('featured');
            });

            // Cập nhật dữ liệu từ cột active vào status
            DB::statement("UPDATE products SET status = CASE WHEN active = 1 THEN 'active' ELSE 'inactive' END");

            // Xóa cột active
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }

        // Xóa cột is_new
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_new');
        });
    }
};
