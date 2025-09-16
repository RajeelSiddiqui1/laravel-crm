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
            $table->unsignedBigInteger('teamlead_id')->nullable()->after('manager_id');

            $table->foreign('teamlead_id')->references('id')->on('team_leads')->onDelete('set null');
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
