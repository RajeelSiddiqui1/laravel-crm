<?php
// database/migrations/xxxx_xx_xx_create_shared_tasks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shared_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('project_managers')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('project_managers')->onDelete('cascade');
            $table->foreignId('owner_task_id')->constrained('owner_tasks')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_tasks');
    }
};
