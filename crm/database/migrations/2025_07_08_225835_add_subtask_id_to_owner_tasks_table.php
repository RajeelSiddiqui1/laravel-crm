<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('owner_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('subtask_id')->nullable()->after('employee_id');

            $table->foreign('subtask_id')
                  ->references('id')
                  ->on('subtasks')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('owner_tasks', function (Blueprint $table) {
            $table->dropForeign(['subtask_id']);
            $table->dropColumn('subtask_id');
        });
    }
};
