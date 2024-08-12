<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublisherController extends Controller
{
    public function edit($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json([
                'success' => false,
                'message' => 'Publisher not found',
            ], 404);
        }

        return response()->json($publisher);
    }

    public function store(PublisherRequest $request)
    {
        try {
            DB::beginTransaction();

            Publisher::create([
                'name' => $request->name,
                'topic' => $request->topic,
                'on_message' => $request->on_message,
                'off_message' => $request->off_message,
                'device_id' => $request->device_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menyimpan data',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data ' . $th->getMessage()
            ], 500);
        }
    }
}
