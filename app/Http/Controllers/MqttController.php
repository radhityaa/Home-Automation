<?php

namespace App\Http\Controllers;

use App\Http\Requests\MqttRequest;
use App\Models\Mqtt;
use Illuminate\Http\Request;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttController extends Controller
{
    public function index()
    {
        $data = Mqtt::first();
        return view('admin.settings.mqtt.index', compact('data'));
    }

    public function update(Mqtt $mqtt, MqttRequest $request)
    {
        $mqtt->fill($request->only('host', 'port', 'client_id', 'username', 'password'));
        $mqtt->save();

        return response()->json([
            'success' => true,
            'message' => 'MQTT berhasil diubah.'
        ]);
    }

    public function check(Request $request)
    {
        try {
            $mqtt = Mqtt::first();

            $connectionSettings = (new ConnectionSettings)
                ->setUsername($mqtt->username)
                ->setPassword($mqtt->password)
                ->setKeepAliveInterval(60);

            $client = new MqttClient($mqtt->host, $mqtt->port, $mqtt->client_id);
            $client->connect($connectionSettings);

            // Jika koneksi berhasil
            $client->disconnect();
            return response()->json(['message' => 'Koneksi berhasil!'], 200);
        } catch (\Exception $e) {
            // Jika koneksi gagal
            return response()->json(['message' => 'Koneksi gagal: ' . $e->getMessage()], 500);
        }
    }
}
