<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xogames', function (Blueprint $table) {
            $table->id();
            $table->string('user_identifier');
            $table->string('external_id');
            $table->text('result')->nullable(); // This will change in future. Just a temporary holder.
            $table->dateTime('result_date')->nullable();
            $table->tinyInteger('status'); // 1 means webhook recieved, 0 result pending
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xogames');
    }
};
