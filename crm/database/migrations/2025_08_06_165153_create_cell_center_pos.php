<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_center_pos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // Add foreign key
            $table->json('comment')->nullable();
            $table->json('name')->nullable();
            $table->json('business_name')->nullable();
            $table->json('business_number')->nullable();
            $table->json('personal_number')->nullable();
            $table->json('personal_email')->nullable();
            $table->json('business_email')->nullable();
            $table->json('address')->nullable();
            $table->json('provider')->nullable();
            $table->json('category_pos')->nullable();
            $table->json('pos_type')->nullable();
            $table->json('debt')->nullable();
            $table->json('credit')->nullable();
            $table->json('rental')->nullable();
            $table->json('business_type')->nullable();
            $table->json('date')->nullable();
            $table->json('time')->nullable();
            $table->json('status')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_center_pos');
    }
};