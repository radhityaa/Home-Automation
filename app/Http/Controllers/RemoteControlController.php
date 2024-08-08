<?php

namespace App\Http\Controllers;

use App\Models\Relay;
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
        return view('remote-control.index', compact('mqttStatus'));
    }

    public function light()
    {
        $mqttStatus = $this->mqttService->getStatus();
        return view('remote-control.light', compact('mqttStatus'));
    }
}
