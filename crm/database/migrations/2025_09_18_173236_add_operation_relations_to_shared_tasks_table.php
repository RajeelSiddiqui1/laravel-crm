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
            // Manager foreign key
            $table->unsignedBigInteger('operation_manager_id')->nullable()->after('assigned_employee_id');
            $table->foreign('operation_manager_id')
                ->references('id')->on('project_managers')
                ->onDelete('cascade');

            // Teamlead foreign key
            $table->unsignedBigInteger('operation_teamlead_id')->nullable()->after('operation_manager_id');
            $table->foreign('operation_teamlead_id')
                ->references('id')->on('team_leads')
                ->onDelete('cascade');

            // Employee foreign key
            $table->unsignedBigInteger('operation_employee_id')->nullable()->after('operation_teamlead_id');
            $table->foreign('operation_employee_id')
                ->references('id')->on('employees')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            $table->dropForeign(['operation_manager_id']);
            $table->dropForeign(['operation_teamlead_id']);
            $table->dropForeign(['operation_employee_id']);

            $table->dropColumn(['operation_manager_id', 'operation_teamlead_id', 'operation_employee_id']);
        });
    }



};
