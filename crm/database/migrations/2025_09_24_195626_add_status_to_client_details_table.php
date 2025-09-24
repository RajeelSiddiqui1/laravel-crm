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
    Schema::table('client_details', function (Blueprint $table) {
        $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
    });
}

public function down()
{
    Schema::table('client_details', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
