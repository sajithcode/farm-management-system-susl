<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchSlaughtersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_slaughters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->date('slaughter_date');
            $table->integer('count'); // Number of animals slaughtered
            $table->integer('batch_age_days'); // Age of batch in days when slaughtered
            $table->decimal('total_weight', 10, 2)->nullable(); // Total weight of slaughtered animals
            $table->decimal('live_weight', 10, 2)->nullable(); // Live weight
            $table->decimal('dressed_weight', 10, 2)->nullable(); // Dressed weight
            $table->string('purpose')->nullable(); // Purpose of slaughter
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
        Schema::dropIfExists('batch_slaughters');
    }
}
