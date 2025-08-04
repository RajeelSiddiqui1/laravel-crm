<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/xxxx_xx_xx_add_account_id_to_owner_tasks_table.php
public function up()
{
    Schema::table('owner_tasks', function (Blueprint $table) {
        $table->unsignedBigInteger('account_id')->nullable();
        $table->foreign('account_id')
              ->references('id')->on('accounts')
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('owner_tasks', function (Blueprint $table) {
        $table->dropForeign(['account_id']);
        $table->dropColumn('account_id');
    });
}
};
