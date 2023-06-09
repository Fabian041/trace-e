<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\TraceKanban;
use App\Models\KanbanMaster;
use App\Models\NgMaster;
use App\Models\TraceAntenna;
use App\Models\TraceNg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TraceabilityController extends Controller
{
    // traceabilty dashboard
    public function traceIndex()
    {
        $currentDate = date('Y-m-d');

        $ok = TraceAntenna::where('date', 'LIKE', '%' . $currentDate . '%')->count();
        $ng = TraceNg::where('date', 'LIKE', '%' . $currentDate . '%')->count();

        return view('traceability.dashboard',[
            'ok' => $ok,
            'ng' => $ng,
            'total' => $ng + $ok
        ]);
    }

    // modul antenna
    public function index()
    {
        return view('traceability.electric.antenna.fg');
    }

    public function trace($code)
    {

        // substring shot from code
        $shot = substr($code, 17, 4);
        $modelCode = substr($code, 9, 2);

        // search the back number based on model code
        $backNumber = KanbanMaster::select('back_number')->where('model_code', $modelCode)->first();
        if ($backNumber == null) {
            $backNumber = 'unknown';
        } else {
            $backNumber = $backNumber->back_number;
        }

        // antenna 
        $partOk = DB::table('trace_antennas')
            ->join('trace_kanbans', 'trace_antennas.kanban_id', '=', 'trace_kanbans.id')
            ->join('trace_kanban_masters', 'trace_kanbans.master_id', '=', 'trace_kanban_masters.id')
            ->select('trace_kanban_masters.back_number', 'trace_antennas.date', 'trace_antennas.npk')
            ->where('trace_antennas.code', $code)
            ->first();

        //ng
        $partNg = TraceNg::where('code', $code)->first();


        if ($partOk == null && $partNg == null) {
            return [
                'status' => 'null'
            ];
        } else if ($partOk != null && $partNg == null) {
            return [
                'status' => 'successOk',
                'date' => $partOk->date,
                'npk' => $partOk->npk,
                'model' => $partOk->back_number,
                'shot' => $shot

            ];
        } else if ($partOk == null && $partNg != null) {
            return [
                'status' => 'successNg',
                'date' => $partNg->date,
                'npk' => $partNg->npk,
                'model' => $backNumber,
                'shot' => $shot
            ];
        }
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
        $serialNumber = $request->serialNumber;
        $backNumber = $request->backNumber;
        $code = $request->code;

        // substring the part code
        $model = substr($request->code, 9, 2);

        // search kanban id based on serial number
        $kanban = TraceKanban::select('id')->where('serial_number', $serialNumber)->first();

        // search model code based on back number
        $modelCode = KanbanMaster::select('model_code')->where('back_number', $backNumber)->first();

        // check if the code is exists in trace antenna inside spesific kanban
        $checkPart = TraceAntenna::where('code', $code)->first();
        $checkNgPart = TraceNg::where('code', $code)->first();

        if ($checkPart != null || $checkNgPart != null) {
            return [
                'status' => 'exist'
            ];
        }

        try {
            DB::beginTransaction();

            // interlock part for spesific model
            if ($modelCode->model_code == $model) {
                // insert part into trace antenna
                TraceAntenna::create([
                    'kanban_id' => $kanban->id,
                    'code' => $request->code,
                    'npk' => auth()->user()->npk,
                    'date' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            } else {
                return ['status' => 'notMatch'];
            }

            // get current qty for spesific kanban series
            $qty = TraceAntenna::where('kanban_id', $kanban->id)->count();

            $key_ok = 'electric_antenna_ok';
            $key_total = 'electric_antenna_total';

            // cache ok
            if (Cache::has($key_ok)) {
                $cache_ok = Cache::get($key_ok);
                if (!isset($cache_ok[date('Y-m-d')])) {
                    $cache_ok = [];
                    $cache_ok = [
                        date('Y-m-d') => [
                            'counter_ok' => 1
                        ]
                    ];
                } else {
                    $cache_ok[date('Y-m-d')]['counter_ok'] += 1;
                }
            } else {
                $cache_ok = [
                    date('Y-m-d') => [
                        'counter_ok' => 1
                    ]
                ];
            }

            // cache total
            if (Cache::has($key_total)) {
                $cache_total = Cache::get($key_total);
                if (!isset($cache_total[date('Y-m-d')])) {
                    $cache_total = [];
                    $cache_total = [
                        date('Y-m-d') => [
                            'counter_total' => 1
                        ]
                    ];
                } else {
                    $cache_total[date('Y-m-d')]['counter_total'] += 1;
                }
            } else {
                $cache_total = [
                    date('Y-m-d') => [
                        'counter_total' => 1
                    ]
                ];
            }

            $key_ng = 'electric_antenna_ng';
            $cache_ng = Cache::get($key_ng, []);
            $counter_ng = isset($cache_ng[date('Y-m-d')]['counter_ng']) ? $cache_ng[date('Y-m-d')]['counter_ng'] : 0;

            // reset kanban when quantity inside kanban already 100
            if ($qty == 100) {
                $reset = TraceAntenna::where('kanban_id', $kanban->id)->update([
                    'kanban_id' => NULL
                ]);
            }

            Cache::forever($key_ok, $cache_ok);
            Cache::forever($key_total, $cache_total);

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
            "counter_ok"   => $cache_ok[date('Y-m-d')]['counter_ok'],
            "counter_total"   => $cache_total[date('Y-m-d')]['counter_total'],
            "counter_ng"   => $counter_ng,
            "progress" => $qty,
            "code" => $request->code
        ];
    }

    public function ngCheck(Request $request)
    {
        // get id ng
        $ngId = $request->ng;

        //check ig ng exist
        $ng = NgMaster::where('id', $ngId)->first();

        if ($ng == null) {
            return [
                'status' => 'error',
                'message' => 'ID NG Tidak Ditemukan!'
            ];
        }

        return ['status' => 'success'];
    }

    public function ngAntenna($ngId)
    {
        $ngName = NgMaster::select('name')->where('id', $ngId)->first();

        return view('traceability.electric.antenna.ng', [
            'ngName' => $ngName,
            'ngId' => $ngId
        ]);
    }

    public function storeNgAntenna($ngId, $part)
    {
        // check if part is exist
        $ngTrace = TraceNg::where('code', $part)->first();

        // get kanban id (serial number of kanban)
        $kanban = TraceAntenna::select('kanban_id')->where('code', $part)->first();

        if ($ngTrace != null) {
            return [
                'status' => 'error',
                'message' => 'Part Sudah Pernah Discan!'
            ];
        }

        try {
            DB::beginTransaction();

            // delete from ok trace table
            $status = TraceAntenna::where('code', $part)->delete();

            // insert into trace ng database
            TraceNg::create([
                'ng_id' => $ngId,
                'code' => $part,
                'npk' => auth()->user()->npk,
                'date' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            // get current qty for spesific kanban series
            $qty = TraceAntenna::where('kanban_id', $kanban->kanban_id)->count();

            $key_ok = 'electric_antenna_ok';
            $key_ng = 'electric_antenna_ng';
            $key_total = 'electric_antenna_total';

            if (Cache::has($key_ng)) {
                $cache_ng = Cache::get($key_ng);
                if (!isset($cache_ng[date('Y-m-d')])) {
                    $cache_ng = [];
                    $cache_ng = [
                        date('Y-m-d') => [
                            'counter_ng' => 1
                        ]
                    ];
                } else {
                    $cache_ng[date('Y-m-d')]['counter_ng'] += 1;
                }
            } else {
                $cache_ng = [
                    date('Y-m-d') => [
                        'counter_ng' => 1
                    ]
                ];
            }

            // Decrease the value of counter_ok
            if (Cache::has($key_ok)) {
                $cache_ok = Cache::get($key_ok);
                if (isset($cache_ok[date('Y-m-d')])) {
                    $cache_ok[date('Y-m-d')]['counter_ok'] -= 1;
                    if ($cache_ok[date('Y-m-d')]['counter_ok'] < 0) {
                        $cache_ok[date('Y-m-d')]['counter_ok'] = 0;
                    }
                }
            } else {
                $cache_ok = [];
            }

            Cache::forever($key_ng, $cache_ng);
            Cache::forever($key_ok, $cache_ok);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                "status" => "error",
                "messege" => $th->getMessage()
            ];
        }

        return [
            'status' => 'success',
            "counter_ng"   => $cache_ng[date('Y-m-d')]['counter_ng'],
            "counter_ok"   => $cache_ok[date('Y-m-d')]['counter_ok'],
            'progress' => $qty,
            'message' => 'Part NG Berhasil Disimpan'
        ];
    }
}
