<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Publisher;
use App\Models\Relay;
use App\Models\State;
use App\Services\MqttService;
use Illuminate\Http\Request;

class RemoteControlController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
        $this->mqttService->connect();
    }

    public function index()
    {
        $mqttStatus = $this->mqttService->getStatus();
        $devices = Device::all();

        return view('remote-control.index', compact('mqttStatus', 'devices'));
    }

    public function light($id)
    {
        $mqttStatus = $this->mqttService->getStatus();
        $device = Device::find($id);

        return view('remote-control.light', compact('mqttStatus', 'device'));
    }
}
