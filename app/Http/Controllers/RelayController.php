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
        $topic = $request->topic;
        $message = $request->message;
        $publisher_id = $request->publisher_id;

        $this->mqttService->publish($topic, $message, $publisher_id);

        return response()->json([
            'success' => true,
            'topic' => $topic,
            'message' => $message,
        ]);
    }

    public function getRelayStatus()
    {
        $relays = Relay::all();
        return response()->json($relays);
    }
}
