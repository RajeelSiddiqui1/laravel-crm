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
    Schema::table('group_messages', function (Blueprint $table) {
        $table->unsignedBigInteger('account_id')->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('group_messages', function (Blueprint $table) {
        $table->dropColumn([ 'account_type']);
    });
}

};
