<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_subtasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtask_id'); // FK
            $table->json('comments')->nullable();     // array of comments per lead
            $table->json('statuses')->nullable();     // array of statuses per lead
            $table->json('attachments')->nullable();  // array of attachment URLs per lead
            $table->timestamps();

            $table->foreign('subtask_id')->references('id')->on('subtasks')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_subtasks');
    }
};
