<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages_teamlead_employee', function (Blueprint $table) {
            $table->id();

            // Sender
            $table->unsignedBigInteger('sender_id');
            $table->enum('sender_type', ['employees', 'team_leads']);

            // Receiver
            $table->unsignedBigInteger('receiver_id');
            $table->enum('receiver_type', ['employees', 'team_leads']);

            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_teamlead_employee');
    }
};
