<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the column without constraint
        Schema::table('refuel_records', function (Blueprint $table) {
            $table->uuid('vehicle_id')->after('id')->nullable();
        });

        // Get the first vehicle id (or a default one)
        $firstVehicleId = DB::table('vehicles')->first()?->id ?? 1;
        
        // Update existing records
        DB::table('refuel_records')->whereNull('vehicle_id')->update(['vehicle_id' => $firstVehicleId]);

        // Now add the foreign key constraint
        Schema::table('refuel_records', function (Blueprint $table) {
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->onDelete('cascade');

            // Make vehicle_id required after setting default value
            $table->uuid('vehicle_id')->nullable(false)->change();

            // Make time field nullable
            $table->time('time')->nullable()->change();

            // Rename columns to match form fields
            $table->renameColumn('unit_price', 'price_per_unit');
            $table->renameColumn('litres', 'amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refuel_records', function (Blueprint $table) {
            // Revert column names
            $table->renameColumn('price_per_unit', 'unit_price');
            $table->renameColumn('amount', 'litres');

            // Drop vehicle_id column
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');

            // Make time field required again
            $table->time('time')->nullable(false)->change();
        });
    }
};