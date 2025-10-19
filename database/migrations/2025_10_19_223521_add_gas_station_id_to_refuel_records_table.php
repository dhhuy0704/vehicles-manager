<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('refuel_records', function (Blueprint $table) {
            $table->foreignId('gas_station_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refuel_records', function (Blueprint $table) {
            $table->dropForeign(['gas_station_id']);
            $table->dropColumn('gas_station_id');
        });
    }
};
