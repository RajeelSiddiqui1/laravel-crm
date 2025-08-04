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
        Schema::table("subtasks", function (Blueprint $table) {
               $table->unsignedBigInteger('form_task_id')->nullable();
               $table->foreign('form_task_id')->references('id')->on('cell_center_pos')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
