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
        Schema::table('project_managers', function (Blueprint $table) {
            // Add JSON column to store array of department IDs
            $table->json('department_ids')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_managers', function (Blueprint $table) {
            $table->dropColumn('department_ids');
        });
    }
};
