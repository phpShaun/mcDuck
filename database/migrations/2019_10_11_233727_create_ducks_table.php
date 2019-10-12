<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ducks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->char('name', 50);
            $table->integer('health')->default(10)->unsigned();
            $table->integer('hunger')->default(3)->unsigned();
            $table->decimal('weight', 10, 2)->default(0.4)->unsigned();
            $table->integer('happyness')->default(3)->unsigned();
            $table->enum('sex', ['Male', 'Female']);
            $table->char('status', 20)->default('Healthy');
            $table->tinyInteger('illness')->default(0);
            $table->dateTime('born');
            $table->dateTime('last_fed');
            $table->dateTime('last_interaction');
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
        Schema::dropIfExists('ducks');
    }
}
