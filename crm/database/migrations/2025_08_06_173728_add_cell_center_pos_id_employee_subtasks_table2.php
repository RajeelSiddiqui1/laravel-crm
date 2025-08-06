<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->json('cell_center_pos_data')->nullable(); // JSON column for POS data
            $table->json('cell_center_account_data')->nullable(); // JSON column for Account data
        });
    }

    public function down(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->dropColumn('cell_center_pos_data');
            $table->dropColumn('cell_center_account_data');
        });
    }
};