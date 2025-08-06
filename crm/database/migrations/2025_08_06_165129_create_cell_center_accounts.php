<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_center_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // Add foreign key
            $table->json('driving_license');
            $table->json('email');
            $table->json('phone');
            $table->json('bussiness_number');
            $table->json('corpuration_number');
            $table->json('corpuration_email');
            $table->json('corpuration_documents');
            $table->json('pervious_history');
            $table->json('fees');
            $table->json('status');
            $table->json('comments');
            $table->json('attachments');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_cell_center_accounts');
    }
};