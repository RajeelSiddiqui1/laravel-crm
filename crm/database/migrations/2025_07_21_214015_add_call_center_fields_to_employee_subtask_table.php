<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_num')->nullable();
            $table->string('personal_num')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('business_email')->nullable();
            $table->text('address')->nullable();
            $table->string('perivos')->nullable();
            $table->string('provider')->nullable();
            $table->string('category_pos')->nullable();
            $table->string('pos_type')->nullable();
            $table->string('debt')->nullable();
            $table->string('credit')->nullable();
            $table->string('rentle')->nullable();
            $table->date('oppiomennt_date')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('employee_subtasks', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'business_name',
                'business_num',
                'personal_num',
                'personal_email',
                'business_email',
                'address',
                'perivos',
                'provider',
                'category_pos',
                'pos_type',
                'debt',
                'credit',
                'rentle',
                'oppiomennt_date',
                'date',
                'time',
            ]);
        });
    }
};
