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
        
Schema::create('department_project_manager', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_manager_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('team_lead_id');
            $table->unsignedBigInteger('employ_id');
            $table->timestamps();

            $table->foreign('project_manager_id')->references('id')->on('project_managers')->onDelete('cascade');
            $table->foreign('employ_id')->references('id')->on('employs')->onDelete('cascade');
            $table->foreign('team_lead_id')->references('id')->on('team_leads')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_manager_departments');
    }
};
