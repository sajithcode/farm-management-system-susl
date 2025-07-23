<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualAnimalSlaughtersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_animal_slaughters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_animal_id')->constrained('individual_animals')->onDelete('cascade');
            $table->date('slaughter_date');
            $table->integer('age_in_days'); // Age at time of slaughter
            $table->decimal('live_weight', 8, 2)->nullable(); // Live weight
            $table->decimal('dressed_weight', 8, 2)->nullable(); // Dressed weight
            $table->string('purpose')->nullable(); // Purpose of slaughter
            $table->text('medicine_used')->nullable(); // Medicine/treatment info
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
        Schema::dropIfExists('individual_animal_slaughters');
    }
}
