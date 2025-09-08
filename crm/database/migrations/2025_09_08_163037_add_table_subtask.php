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
        Schema::create('subtasks', function(Blueprint $table){
            $table->id();
            $table->foreignId('owner_task_id')->constrained('owner_tasks')->onDelete('cascade');
            $table->foreignId('team_lead_id')->constrained('team_leads')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->string('attachments');
            $table->integer('lead');
            $table->enum('teamlead_status',['pending','completed','rejected','late'])->default('pending');
            $table->enum('employee_status',['pending','completed','rejected','late'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
