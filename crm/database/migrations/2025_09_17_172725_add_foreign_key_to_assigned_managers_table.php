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
             Schema::table('shared_tasks', function (Blueprint $table) {
              
        $table->unsignedBigInteger('assigned_teamlead_id')->nullable()->after('assigend_manager_id');

        $table->foreign('assigned_teamlead_id')
              ->references('id')->on('team_leads')
              ->onDelete('cascade');
        });
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
