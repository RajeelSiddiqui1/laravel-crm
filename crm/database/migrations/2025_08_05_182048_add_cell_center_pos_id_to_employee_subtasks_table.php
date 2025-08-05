<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCellCenterPosIdToEmployeeSubtasksTable extends Migration
{
    public function up()
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->foreignId('cell_center_pos_id')->nullable()->constrained('cell_center_pos')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->dropForeign(['cell_center_pos_id']);
            $table->dropColumn('cell_center_pos_id');
        });
    }
}