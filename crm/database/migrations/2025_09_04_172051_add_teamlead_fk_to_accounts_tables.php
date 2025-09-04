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
   
    Schema::table('accounts_t1', function (Blueprint $table) {
        $table->unsignedBigInteger('team_lead_id')->nullable()->after('id');

        $table->foreign('team_lead_id')
              ->references('id')->on('team_leads')
              ->onDelete('cascade');
    });

    Schema::table('accounts_t2', function (Blueprint $table) {
        $table->unsignedBigInteger('team_lead_id')->nullable()->after('id');

        $table->foreign('team_lead_id')
              ->references('id')->on('team_leads')
              ->onDelete('cascade');
    });
}

public function down()
{
  
    Schema::table('accounts_t1', function (Blueprint $table) {
        $table->dropForeign(['team_lead_id']);
        $table->dropColumn('team_lead_id');
    });

    Schema::table('accounts_t2', function (Blueprint $table) {
        $table->dropForeign(['team_lead_id']);
        $table->dropColumn('team_lead_id');
    });
}


    /**
     * Reverse the migrations.
     */
    
};
