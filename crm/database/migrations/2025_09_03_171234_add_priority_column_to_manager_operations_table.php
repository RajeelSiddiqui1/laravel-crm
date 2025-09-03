<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('manager_operations', function (Blueprint $table) {
            $table->string('priority')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('manager_operations', function (Blueprint $table) {
            $table->dropColumn('priority');
        });
    }
};
