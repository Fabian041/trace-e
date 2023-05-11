<?php

namespace App\Http\Controllers;

use App\Models\KanbanMaster;
use App\Models\TraceAntenna;
use App\Models\TraceKanban;
use Illuminate\Http\Request;

class TraceabilityController extends Controller
{
    // modul antenna
    public function index()
    {
        return view('traceability.electric.antenna.fg');
    }

    public function storeKanban(Request $request)
    {
        // substring the kanban code
        $backNumber = substr($request->kanban, 100, 4);
        $serialNumber = substr($request->kanban, 123, 4);

        // check if master kanban exist
        $kanbanMaster = KanbanMaster::where('back_number', $backNumber)->first();

        // get id of spesific serial number kanban master at trace kanban table
        $kanbanId = TraceKanban::select('id')->where('serial_number', $serialNumber)->first();

        if ($kanbanId == null) {
            return array(
                'status' => 'notregistered',
                'serial_number' => $serialNumber
            );
        }

        // check if kanban not contain part by checking the trace anntenna table at kanban_id column
        $kanbans = TraceAntenna::where('kanban_id', $kanbanId->id)->count();

        try {
            if ($kanbanMaster !== null  || $kanbanId !== null) {
                if ($kanbans == 100) {
                    return array(
                        'status' => 'Kanbannotreset',
                    );
                } else if ($kanbans > 0 && $kanbans <= 100) {
                    return array(
                        'status' => 'unfinished',
                    );
                } else {
                    return array(
                        'status' => 'success',
                        'backNumber' => $backNumber,
                        'serialNumber' => $serialNumber
                    );
                }
            } else {
                return array(
                    'status' => 'notregistered',
                );
            }
        } catch (\Throwable $th) {
            return array(
                'status' => 'error',
                'code' => $th->getMessage()
            );
        }
    }
}
