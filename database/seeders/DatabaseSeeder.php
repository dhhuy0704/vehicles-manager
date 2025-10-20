<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Owner;
use App\Models\Vehicle;
use App\Models\GasStation;
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
        $user = User::factory()->create([
            'name'     => 'Admin',
            'password' => bcrypt('123456'),
            'email'    => 'a@b.c',
        ]);

        // Create owner
        $owner = Owner::create([
            'first_name' => 'Tommy',
            'last_name'  => 'Do',
            'email'      => 'tommy@do.com',
            'phone'      => '123-456-7890',
            'gender'     => 'male'
        ]);

        // Create gas station
        $gasStation = GasStation::create([
            'name'     => 'Costco Heritage',
            'location' => 'Heritage Gateway, Calgary, AB',
        ]);

        // Create vehicle
        Vehicle::create([
            'type'          => 'car',
            'name'          => 'My Car',
            'manufacturer'  => 'toyota',
            'model'         => 'Siena',
            'year'          => 2019,
            'fuel_capacity' => 45.0,
            'status'        => 'active',
            'note'          => 'This is my primary vehicle.',
            'owner_id'      => $owner->id,
        ]);
    }
}
