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
        Schema::create('shared_tasks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('visitor_id')->nullable();

            $table->foreign('manager_id')->references('id')->on('project_managers')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('set null');

            $table->string('subtask_id');
            $table->longText('comment');
            $table->enum('status', ['pending', 'deployed', 'on_leave', 'inactive'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_tasks');
    }
};
