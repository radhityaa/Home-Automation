<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

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
}
