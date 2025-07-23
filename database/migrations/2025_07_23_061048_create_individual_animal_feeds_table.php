<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualAnimalFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_animal_feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_animal_id')->constrained('individual_animals')->onDelete('cascade');
            $table->date('feed_date');
            $table->string('feed_type');
            $table->decimal('quantity', 10, 2); // Amount of feed in kg
            $table->string('unit')->default('kg');
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('administered_by'); // Person who fed the animal
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
        Schema::dropIfExists('individual_animal_feeds');
    }
}
