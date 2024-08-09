<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function getState($id)
    {
        return State::where('publisher_id', $id)->first();
    }
}
