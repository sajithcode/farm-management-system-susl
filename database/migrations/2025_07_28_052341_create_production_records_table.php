<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_records', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->string('batch_id');
            $table->enum('product_type', ['eggs', 'meat', 'milk', 'other']);
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 50);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('batch_id')->references('batch_id')->on('batches')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index(['production_date', 'batch_id']);
            $table->index('product_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_records');
    }
}
