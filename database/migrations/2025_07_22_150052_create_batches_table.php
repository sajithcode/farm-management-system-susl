<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique(); // Custom batch identifier
            $table->string('name')->nullable(); // Optional batch name
            $table->string('animal_type'); // Chicken, Pig, Cattle, etc.
            $table->date('start_date');
            $table->integer('initial_count');
            $table->integer('current_count');
            $table->string('supplier')->nullable();
            $table->string('responsible_person');
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
        Schema::dropIfExists('batches');
    }
}
