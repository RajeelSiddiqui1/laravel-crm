<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->text('comment')->nullable();
            $table->string('attachment')->nullable(); // for storing file path
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected', 'late'])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropColumn(['comment', 'attachment', 'status']);
        });
    }
};
