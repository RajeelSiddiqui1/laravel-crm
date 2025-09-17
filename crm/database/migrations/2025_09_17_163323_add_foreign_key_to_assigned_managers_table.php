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
              
        $table->unsignedBigInteger('assigend_manager_id')->nullable()->after('manager_id');

        $table->foreign('assigend_manager_id')
              ->references('id')->on('project_managers')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            //
        });
    }
};
