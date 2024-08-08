<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RemoteControlController extends Controller
{
    public function index()
    {
        return view('remote-control.index');
    }

    public function light()
    {
        return view('remote-control.light');
    }
}
