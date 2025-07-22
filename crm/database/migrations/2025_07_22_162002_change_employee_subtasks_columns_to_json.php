<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->json('name')->nullable()->change();
            $table->json('business_name')->nullable()->change();
            $table->json('business_num')->nullable()->change();
            $table->json('personal_num')->nullable()->change();
            $table->json('personal_email')->nullable()->change();
            $table->json('business_email')->nullable()->change();
            $table->json('address')->nullable()->change();
            $table->json('perivos')->nullable()->change();
            $table->json('provider')->nullable()->change();
            $table->json('category_pos')->nullable()->change();
            $table->json('pos_type')->nullable()->change();
            $table->json('debt')->nullable()->change();
            $table->json('credit')->nullable()->change();
            $table->json('rentle')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('business_name')->nullable()->change();
            $table->string('business_num')->nullable()->change();
            $table->string('personal_num')->nullable()->change();
            $table->string('personal_email')->nullable()->change();
            $table->string('business_email')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('perivos')->nullable()->change();
            $table->string('provider')->nullable()->change();
            $table->string('category_pos')->nullable()->change();
            $table->string('pos_type')->nullable()->change();
            $table->string('debt')->nullable()->change();
            $table->string('credit')->nullable()->change();
            $table->string('rentle')->nullable()->change();
        });
    }
};
