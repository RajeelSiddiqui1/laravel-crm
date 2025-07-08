<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();

            // Assigned employee
            $table->unsignedBigInteger('assigned_employee_id');
            $table->foreign('assigned_employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
