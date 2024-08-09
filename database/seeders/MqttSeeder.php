<?php

namespace Database\Seeders;

use App\Models\Mqtt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MqttSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mqtt::create([
            'host' => '143.198.212.153',
            'port' => 1883,
            'client_id' => 'ayasya-home-automation',
            'username' => 'ayasyatech',
            'password' => 'u@Zm3mTR7Puh'
        ]);
    }
}
