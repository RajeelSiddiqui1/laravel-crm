<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->json('call_center_pos_ids')->nullable();
            $table->json('call_center_account_ids')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropColumn(['call_center_pos_ids', 'call_center_account_ids']);
        });
    }
};
