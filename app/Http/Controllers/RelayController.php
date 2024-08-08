<?php

namespace App\Http\Controllers;

use App\Models\Relay;
use App\Services\MqttService;
use Illuminate\Http\Request;

class RelayController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    public function controlRelay(Request $request)
    {
        $relay = $request->relay;
        $state = $request->state;

        $topic = 'home/relay' . $relay;
        $message = $state == 'on' ? 'ON' : 'OFF';

        $this->mqttService->publish($topic, $message);

        return response()->json([
            'success' => true,
            'message' => 'Relay ' . $relay . 'is turned ' . $state
        ]);
    }

    public function getRelayStatus()
    {
        $relays = Relay::all();
        return response()->json($relays);
    }
}
