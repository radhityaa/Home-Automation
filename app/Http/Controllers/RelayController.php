<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Models\Relay;
use App\Models\State;
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
        $state = State::where('publisher_id', $publisher_id)->first();
        $publisher = Publisher::find($publisher_id);

        $this->mqttService->publish($topic, $message, $publisher_id);

        if ($state) {
            switch ($state->state) {
                case 'OFF':
                    $resposenMessage = $publisher->on_message;
                    break;
                case 'ON':
                    $resposenMessage = $publisher->off_message;
            }
        } else {
            $resposenMessage = $publisher->on_message;
        }

        return response()->json([
            'success' => true,
            'topic' => $topic,
            'message' => $resposenMessage,
        ]);
    }

    public function getRelayStatus()
    {
        $relays = Relay::all();
        return response()->json($relays);
    }
}
