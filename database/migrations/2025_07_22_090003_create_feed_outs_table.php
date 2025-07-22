<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_outs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('feed_type_id')->constrained('feed_types')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->string('issued_to'); // Person receiving the feed
            $table->string('location'); // Batch or Individual Animal location
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
        Schema::dropIfExists('feed_outs');
    }
}
