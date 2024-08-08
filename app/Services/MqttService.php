<?php

namespace App\Services;

use App\Models\Relay;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\Facades\MQTT;

class MqttService
{
    public function publish($topic, $message)
    {
        $mqtt = MQTT::connection();
        $mqtt->publish($topic, $message);

        $relay_number = substr($topic, -1);
        Relay::updateOrCreate(
            ['relay_number' => $relay_number],
            ['state' => $message]
        );
    }

    public function connect()
    {
        try {
            $mqtt = MQTT::connection();
            $mqtt->connect();
            Cache::put('mqtt_status', 'Connected');
        } catch (\Throwable $th) {
            Cache::put('mqtt_status', 'Disconnected');
        }
    }

    public function getStatus()
    {
        return Cache::get('mqtt_status', 'Disconnected');
    }
}
