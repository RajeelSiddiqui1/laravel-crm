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
        Schema::table('owner_tasks', function (Blueprint $table) {
            $table->enum('status3', ['approved','rejected','lated','pending'])
                  ->default('pending')
                  ->after('status2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owner_tasks', function (Blueprint $table) {
            $table->dropColumn('status3');
        });
    }
};
