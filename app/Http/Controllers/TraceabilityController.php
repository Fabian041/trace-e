<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\TraceKanban;
use App\Models\KanbanMaster;
use App\Models\TraceAntenna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
                        'code' => $backNumber . '#' . $serialNumber
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

    public function storePart(Request $request)
    {
        $serialNumber = $request->kanban;

        // substring the part code
        $code = substr($request->code, 0, 11);

        // search kanban id based on serial number
        $kanban = TraceKanban::select('id')->where('serial_number', $serialNumber)->first();

        // check if the code is exists in trace antenna inside spesific kanban
        $checkPart = TraceAntenna::where('code', $code)->first();

        if ($checkPart != null) {
            return [
                'status' => 'exist'
            ];
        }

        try {
            DB::beginTransaction();

            // insert part into trace antenna
            TraceAntenna::create([
                'kanban_id' => $kanban->id,
                'code' => $code,
                'npk' => auth()->user()->npk,
                'date' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            $key = 'electric_antenna';
            if (Cache::has($key)) {
                $cache = Cache::get($key);
                if (!isset($cache[date('Y-m-d')])) {
                    $cache = [];
                    $cache = [
                        date('Y-m-d') => [
                            'counter' => 1
                        ]
                    ];
                } else {
                    $cache[date('Y-m-d')]['counter'] += 1;
                }
            } else {
                $cache = [
                    date('Y-m-d') => [
                        'counter' => 1
                    ]
                ];
            }

            Cache::forever($key, $cache);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                "status" => "error",
                "messege" => $th->getMessage()
            ];
        }

        return [
            "status" => "success",
            "counter"   => $cache[date('Y-m-d')]['counter'],
            "code" => $code
        ];
    }
}
