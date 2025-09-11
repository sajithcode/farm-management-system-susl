<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medicine_records', function (Blueprint $table) {
            $table->decimal('cost', 10, 2)->nullable()->after('medicine_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_records', function (Blueprint $table) {
            $table->dropColumn('cost');
        });
    }
};
