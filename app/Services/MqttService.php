<?php

namespace App\Services;

use App\Models\Relay;
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
}
