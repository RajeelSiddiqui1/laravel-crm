<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_add_shared_columns_to_owner_tasks_table.php
public function up()
{
    Schema::table('owner_tasks', function (Blueprint $table) {
        $table->unsignedBigInteger('shared_with_manager_id')->nullable()->after('project_manger_task');
        $table->boolean('shared_task')->default(false)->after('shared_with_manager_id');

        $table->foreign('shared_with_manager_id')->references('id')->on('project_managers')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('owner_tasks', function (Blueprint $table) {
        $table->dropForeign(['shared_with_manager_id']);
        $table->dropColumn(['shared_with_manager_id', 'shared_task']);
    });
}

};
