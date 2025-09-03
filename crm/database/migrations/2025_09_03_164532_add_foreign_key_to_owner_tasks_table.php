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
        Schema::table('owner_tasks', function(Blueprint $table){
            $table->unsignedBigInteger('manager_operation_id')->nullable()->after('account_hst_id');
            $table->foreign('manager_operation_id')->references('id')->on('manager_operations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_tasks', function (Blueprint $table) {
            $table->dropForeign(['manager_operation_id']);
            $table->dropColumn('manager_operation_id');
        });
    }
};
