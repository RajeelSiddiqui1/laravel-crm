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
        Schema::create('accounts_hst', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('phone');
            $table->string('email');
            $table->string('corpration_name')->nullable();
            $table->string('corpration_number')->nullable();
            $table->string('attachments')->nullable();
            $table->date('due_date');
            $table->string('nature_of_bussiness');
            $table->enum('priority', ['low', 'high', 'medium'])->default('medium');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_hst');
    }
};
