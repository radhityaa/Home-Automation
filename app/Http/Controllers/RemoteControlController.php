<?php

namespace App\Http\Controllers;

use App\Models\Relay;
use App\Models\State;
use App\Models\Device;
use App\Models\Publisher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\MqttService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateDeviceRequest;

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

    public function view(Device $device)
    {
        return view('remote-control.view', compact('device'));
    }

    public function store(CreateDeviceRequest $request)
    {
        try {
            DB::beginTransaction();
            Device::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => Str::slug($request->name) . '-' . Str::random(6),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Device Berhasil Ditambahkan'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan: ' . $th->getMessage()
            ], 400);
        }
    }

    public function destroy(Device $device)
    {
        try {
            DB::beginTransaction();
            $device->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Device Berhasil Dihapus'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan: ' . $th->getMessage()
            ], 400);
        }
    }

    public function edit(Device $device)
    {
        return response()->json($device);
    }

    public function update(CreateDeviceRequest $request, Device $device)
    {
        try {
            DB::beginTransaction();
            $device->update([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => Str::slug($request->name) . '-' . Str::random(6),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Device Berhasil Diubah'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan: ' . $th->getMessage()
            ], 400);
        }
    }
}
