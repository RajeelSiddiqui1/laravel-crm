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
        Schema::table("owner_tasks", function (Blueprint $table) {
            // Add the column
            $table->unsignedBigInteger("project_manger_task")->nullable()->after("project_manager_id");

            // Add the foreign key constraint
            $table->foreign("project_manger_task")
                ->references("id")
                ->on("project_managers") 
                ->onDelete("set null"); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("owner_tasks", function (Blueprint $table) {
            $table->dropForeign(["project_manger_task"]);
            $table->dropColumn("project_manger_task");
        });
    }
};
