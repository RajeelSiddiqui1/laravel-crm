<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id')->nullable(); // for one-to-one chat
            $table->json('receiver_ids')->nullable(); // for group chat
            $table->unsignedBigInteger('owner_task_id')->nullable(); // foreign key to group task

            $table->text('content')->nullable();
            $table->text('attachments')->nullable(); // Cloudinary file path
            $table->timestamps();

            // Indexes and foreign keys (optional depending on your schema)
            $table->foreign('owner_task_id')->references('id')->on('owner_tasks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
