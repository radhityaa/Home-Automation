<?php

namespace App\Services;

use App\Models\Mqtt as ModelsMqtt;
use App\Models\Relay;
use App\Models\State;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class MqttService
{
    protected $mqttClient;

    public function __construct()
    {
        $this->loadMqttConfig();
    }

    protected function loadMqttConfig()
    {
        $mqtt = ModelsMqtt::first();

        if (!$mqtt) {
            throw new \Exception("MQTT settings not found in the database.");
        }

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($mqtt->username)
            ->setPassword($mqtt->password);

        $this->mqttClient = new MqttClient(
            $mqtt->host,
            $mqtt->port,
            $mqtt->client_id
        );

        $this->mqttClient->connect($connectionSettings);
    }

    public function publish($topic, $message, $publisher_id)
    {
        $this->mqttClient->publish($topic, $message);
        State::updateOrCreate(
            ['publisher_id' => $publisher_id],
            ['state' => $message]
        );
    }

    public function connect()
    {
        try {
            $this->mqttClient->connect();
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
