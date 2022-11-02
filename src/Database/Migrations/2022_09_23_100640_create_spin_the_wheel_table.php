<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spin_the_wheels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('code_id')->unique();
            $table->text('result'); // This will change in future. Just a temporary holder.
            $table->dateTime('result_date');
            $table->tinyInteger('status'); // 1 means action taken, 0 means not taken and still in queue
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spin_the_wheels');
    }
};
