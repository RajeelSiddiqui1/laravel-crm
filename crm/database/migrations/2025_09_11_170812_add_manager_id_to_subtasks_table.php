<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->unsignedBigInteger('manager_id')->nullable()->after('team_lead_id');
            $table->foreign('manager_id')->references('id')->on('project_managers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn('manager_id');
        });
    }
};
