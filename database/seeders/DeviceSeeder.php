<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Device::create([
            'name' => $name = 'Light Switch',
            'slug' => Str::slug($name) . '-' . Str::random('6'),
            'description' => 'On/Off switch for a room'
        ]);
    }
}
