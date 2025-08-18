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
            $table->unsignedBigInteger('account_hst_id')->nullable()->after('id'); 

            // Add foreign key constraint
            $table->foreign('account_hst_id')
                  ->references('id')
                  ->on('accounts_hst')
                  ->onDelete('cascade'); // optional: deletes related rows automatically
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('your_table_name', function (Blueprint $table) {
            $table->dropForeign(['account_hst_id']);
            $table->dropColumn('account_hst_id');
        });
    }
};
