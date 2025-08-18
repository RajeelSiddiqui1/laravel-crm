<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('owner_tasks', function (Blueprint $table) {
            // Add the column
            $table->unsignedBigInteger('account_t1_id')->nullable()->after('id'); 

            // Add foreign key constraint
            $table->foreign('account_t1_id')
                  ->references('id')
                  ->on('accounts_t1')
                  ->onDelete('cascade'); // optional: deletes related rows automatically
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('your_table_name', function (Blueprint $table) {
            $table->dropForeign(['account_t1_id']);
            $table->dropColumn('account_t1_id');
        });
    }
};
