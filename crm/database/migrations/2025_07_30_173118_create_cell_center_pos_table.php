<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellCenterPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cell_center_pos', function (Blueprint $table) {
            $table->id();
            $table->json('comment');
            $table->json('name');
            $table->json('business_name');
            $table->json('business_number');
            $table->json('personal_number');
            $table->json('personal_email');
            $table->json('business_email');
            $table->json('address');
            $table->json('provider');
            $table->json('category_pos');
            $table->json('pos_type');
            $table->json('debut');
            $table->json('credit');
            $table->json('rental');
            $table->json('business_type');
            $table->json('date');
            $table->json('time');
            $table->json('status');
            $table->json('attachments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cell_center_pos');
    }
}