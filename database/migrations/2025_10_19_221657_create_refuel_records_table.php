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
        Schema::create('refuel_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('odometer');
            $table->decimal('unit_price', 8, 3);
            $table->decimal('total_cost', 10, 3);
            $table->decimal('litres', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refuel_records');
    }
};
