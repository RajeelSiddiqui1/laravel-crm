<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
    // New columns
    $table->unsignedBigInteger('cell_center_pos_id')->nullable();
    $table->unsignedBigInteger('cell_center_account_id')->nullable()->after('cell_center_pos_id');

    // Foreign key constraints	
    $table->foreign('cell_center_pos_id')
          ->references('id')->on('cell_center_pos') // 👈 yaha actual table ka naam likho
          ->onDelete('cascade');

    $table->foreign('cell_center_account_id')
          ->references('id')->on('cell_center_accounts') // 👈 exact table name
          ->onDelete('cascade');
});

    }

    public function down(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            $table->dropForeign(['cell_center_pos']);
            $table->dropForeign(['cell_center_accounts']);
            $table->dropColumn(['cell_center_pos', 'cell_center_accounts']);
        });
    }
};
