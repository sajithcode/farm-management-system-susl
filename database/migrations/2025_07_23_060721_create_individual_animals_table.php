<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_animals', function (Blueprint $table) {
            $table->id();
            $table->string('animal_id')->unique(); // Animal identifier
            $table->string('animal_type'); // Cow, Pig, Chicken, etc.
            $table->date('date_of_birth');
            $table->string('supplier')->nullable();
            $table->string('responsible_person');
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->enum('status', ['alive', 'dead', 'slaughtered'])->default('alive');
            $table->decimal('current_weight', 8, 2)->nullable(); // Current weight in kg
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
        Schema::dropIfExists('individual_animals');
    }
}
