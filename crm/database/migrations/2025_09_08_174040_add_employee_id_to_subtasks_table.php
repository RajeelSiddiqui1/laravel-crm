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
        Schema::table('subtasks', function (Blueprint $table) {
            $table->unsignedBigInteger('account_t1_id')->nullable();
            $table->unsignedBigInteger('account_t2_id')->nullable();
            $table->unsignedBigInteger('account_hst_id')->nullable();
            $table->unsignedBigInteger('manager_operation_id')->nullable();

            $table->foreign('account_t1_id')->references('id')->on('accounts_t1')->onDelete('cascade');
            $table->foreign('account_t2_id')->references('id')->on('accounts_t2')->onDelete('cascade');
            $table->foreign('account_hst_id')->references('id')->on('accounts_hst')->onDelete('cascade');
            $table->foreign('manager_operation_id')->references('id')->on('manager_operations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            //
        });
    }
};
