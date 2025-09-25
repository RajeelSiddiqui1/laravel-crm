<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            // 🔹 Column add
            $table->unsignedBigInteger('client_details_id')->nullable()->after('id');

            // 🔹 Foreign key constraint
            $table->foreign('client_details_id')
                  ->references('id')->on('client_details')
                  ->onDelete('cascade'); // delete client -> delete related shared_tasks
        });
    }

    public function down(): void
    {
        Schema::table('shared_tasks', function (Blueprint $table) {
            $table->dropForeign(['client_details_id']);
            $table->dropColumn('client_details_id');
        });
    }
};
