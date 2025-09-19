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
        Schema::table('shared_tasks', function (Blueprint $table) {
            $table->enum('vendor_status', ['pending', 'approved', 'not_approved'])->default('pending');
            $table->enum('machine_status', ['pending', 'deployed', 'cancelled'])->default('pending');
        });
    }

    public function down()
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            $table->dropColumn(['vendor_status', 'machine_status']);
        });
    }
};
