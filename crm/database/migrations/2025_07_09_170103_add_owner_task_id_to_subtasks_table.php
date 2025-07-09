<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->unsignedBigInteger('owner_task_id')->after('id');

            $table->foreign('owner_task_id')
                ->references('id')
                ->on('owner_tasks')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropForeign(['owner_task_id']);
            $table->dropColumn('owner_task_id');
        });
    }
};
