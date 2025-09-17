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
        Schema::table('shared_tasks', function (Blueprint $table) {
              
        $table->unsignedBigInteger('assigned_employee_id')->nullable()->after('assigend_manager_id');

        $table->foreign('assigned_employee_id')
              ->references('id')->on('employees')
              ->onDelete('cascade');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            //
        });
    }
};
