<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cell_center_pos', function (Blueprint $table) {
            $table->dropColumn([
                'comment', 'name', 'business_name', 'business_number', 'personal_number',
                'personal_email', 'business_email', 'address', 'provider', 'category_pos',
                'pos_type', 'debt', 'credit', 'rental', 'business_type', 'date', 'time',
                'status', 'attachments'
            ]);

            $table->text('comment')->nullable();
            $table->string('name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_number')->nullable();
            $table->string('personal_number')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('business_email')->nullable();
            $table->text('address')->nullable();
            $table->string('provider')->nullable();
            $table->string('category_pos')->nullable();
            $table->string('pos_type')->nullable();
            $table->string('debt')->nullable();
            $table->string('credit')->nullable();
            $table->string('rental')->nullable();
            $table->string('business_type')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('status')->nullable();
            $table->text('attachments')->nullable();
        });

        Schema::table('cell_center_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'driving_license', 'email', 'phone', 'bussiness_number', 'corpuration_number',
                'corpuration_email', 'corpuration_documents', 'pervious_history', 'fees',
                'status', 'comments', 'attachments'
            ]);

            $table->string('driving_license')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('bussiness_number')->nullable();
            $table->string('corpuration_number')->nullable();
            $table->string('corpuration_email')->nullable();
            $table->text('corpuration_documents')->nullable();
            $table->text('pervious_history')->nullable();
            $table->string('fees')->nullable();
            $table->string('status')->nullable();
            $table->text('comments')->nullable();
            $table->text('attachments')->nullable();
        });
    }

    public function down(): void
    {
        // optional: reverse columns back to json if needed
    }
};
