<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('item', ['eggs', 'meat']);
            $table->string('source_type'); // 'batch' or 'individual_animal'
            $table->unsignedBigInteger('source_id'); // ID of batch or individual animal
            $table->decimal('quantity', 8, 2);
            $table->decimal('price', 10, 2);
            $table->string('buyer')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id'); // Who recorded the sale
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
