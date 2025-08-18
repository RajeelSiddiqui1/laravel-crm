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
        Schema::create('accounts_t1', function (Blueprint $table) {
            $table->id();
            $table->string('clientname');
            $table->string('period');
            $table->string('driving_license');
            $table->string('sim_number');
            $table->string('bussiness_name');
            $table->date('year');
            $table->string('famliy_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_t1');
    }
};
