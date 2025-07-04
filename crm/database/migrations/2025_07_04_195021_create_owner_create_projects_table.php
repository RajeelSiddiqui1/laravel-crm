<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up()
    {
        Schema::create('owner_projects', function (Blueprint $table) {
            $table->id();

            $table->string('client_name');
            $table->text('description');
            $table->string('client_email');
            $table->string('client_contact');

            // Foreign key to departments
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            // Foreign key to project_managers
            $table->unsignedBigInteger('project_manager_id');
            $table->foreign('project_manager_id')->references('id')->on('project_managers')->onDelete('cascade');

            $table->string('manager_email'); // Duplicate for reference if needed

            $table->date('start_date');
            $table->date('deadline');

            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->enum('status', ['completed', 'in_progress', 'pending', 'canceled'])->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }

};
