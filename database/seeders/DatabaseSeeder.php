<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Owner;
use App\Models\Vehicle;
use App\Models\GasStation;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = new User();
        $user->id = (string) Str::orderedUuid();
        $user->name = 'Admin';
        $user->password = bcrypt('123456');
        $user->email = 'a@b.c';
        $user->save();

        // Create owner
        $owner = new Owner();
        $owner->id = (string) Str::orderedUuid();
        $owner->first_name = 'John';
        $owner->last_name = 'Doe';
        $owner->email = 'john@example.ca';
        $owner->phone = '123-456-7890';
        $owner->gender = 'male';
        $owner->save();

        // Create gas station
        $gasStation = new GasStation();
        $gasStation->id = (string) Str::orderedUuid();
        $gasStation->name = 'Costco Heritage';
        $gasStation->location = 'Heritage Gateway, Calgary, AB';
        $gasStation->save();

                // Create vehicle
        $vehicle = new Vehicle();
        $vehicle->id = (string) Str::orderedUuid();
        $vehicle->type = 'car';
        $vehicle->name = 'My Car';
        $vehicle->manufacturer = 'toyota';
        $vehicle->model = 'Siena';
        $vehicle->year = 2019;
        $vehicle->fuel_capacity = 75.0;
        $vehicle->status = 'active';
        $vehicle->owner_id = $owner->id;
        $vehicle->save();
    }
}
