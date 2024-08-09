<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Lampu Dapur', 'topic' => 'home/relay1', 'device_id' => 1],
            ['name' => 'Relay 2', 'topic' => 'home/relay2', 'device_id' => 1],
            ['name' => 'Relay 3', 'topic' => 'home/relay3', 'device_id' => 1],
            ['name' => 'Relay 4', 'topic' => 'home/relay4', 'device_id' => 1],
        ])->each(fn($q) => Publisher::create($q));
    }
}
