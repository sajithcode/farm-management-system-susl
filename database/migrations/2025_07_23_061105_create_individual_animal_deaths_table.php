<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualAnimalDeathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_animal_deaths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_animal_id')->constrained('individual_animals')->onDelete('cascade');
            $table->date('death_date');
            $table->integer('age_in_days'); // Age at time of death
            $table->decimal('weight', 8, 2)->nullable(); // Weight at time of death
            $table->string('cause')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('individual_animal_deaths');
    }
}
