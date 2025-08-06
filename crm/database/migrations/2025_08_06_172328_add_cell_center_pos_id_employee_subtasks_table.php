<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->unsignedBigInteger('cell_center_pos_id2')->nullable();
            $table->foreign('cell_center_pos_id2')->references('id')->on('cell_center_pos')->onDelete('set null');
            $table->unsignedBigInteger('cell_center_account_id')->nullable();
            $table->foreign('cell_center_account_id')->references('id')->on('cell_center_accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->dropForeign(['cell_center_pos_id2']);
            $table->dropColumn('cell_center_pos_id2');
            $table->dropForeign(['cell_center_account_id']);
            $table->dropColumn('cell_center_account_id');
        });
    }
};
