<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine_records', function (Blueprint $table) {
            $table->id();
            $table->date('medicine_date');
            $table->enum('apply_to', ['batch', 'individual']);
            $table->string('animal_id')->nullable(); // For individual animals
            $table->unsignedBigInteger('batch_id')->nullable(); // For batch animals
            $table->string('medicine_name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('ml');
            $table->decimal('cost_per_unit', 10, 2)->nullable();
            $table->string('administered_by');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['medicine_date', 'apply_to']);
            $table->index(['animal_id']);
            $table->index(['batch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine_records');
    }
};
