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
      Schema::table('owner_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable()->after('project_manager_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->unsignedBigInteger('team_lead_id')->nullable()->after('employee_id');
            $table->foreign('team_lead_id')->references('id')->on('team_leads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_tasks', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');

            $table->dropForeign(['team_lead_id']);
            $table->dropColumn('team_lead_id');
        });
    }
};
