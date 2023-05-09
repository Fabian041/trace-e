<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TraceabilityController extends Controller
{
    // modul antenna
    public function index()
    {
        return view('traceability.electric.antenna.fg');
    }
}
