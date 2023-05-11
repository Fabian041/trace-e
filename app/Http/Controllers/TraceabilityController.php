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
    public function index_ng()
    {
        return view('traceability.electric.antenna.ng');
    }
}
