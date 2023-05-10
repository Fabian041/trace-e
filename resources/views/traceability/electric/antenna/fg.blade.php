@extends('layouts.root.traceability')

@section('main')    

<div class="row mt-5">
    <div class="col-12 col-sm-12 col-md-12">
        <h5 class="mb-3 text-right"><span class="badge badge-dark" style="border-radius: 7px !important">Welcome, {{ auth()->user()->name }}</span></h5>
        <div class="shadow hero bg-white text-dark rounded-3">
            <div class="hero-inner">
                <input id="code" type="text" class="form-control" name="code" tabindex="1" placeholder="scan part here..." required autofocus>
                
                <div class="row mt-3">
                    <div class="col-md-2" style="margin-top: 2.5rem">
                        <p style="color: #595757; font-size:1.5rem; font-weight: bold">Line</p>
                        <h1 class="text-dark" style="font-weight: 800 !impoertant">ASAN01</h1>
                    </div>
                    <div class="col-md-10">
                        <div class="hero bg-primary text-dark">
                            <div class="hero-inner">
                                <h5 class="text-left" style="color: #ffffff; margin-top:-1.5rem">Part Scanned</h5>
                                <h1 class="text-center" style="color: #ffffff">-</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-10 col-sm-10 col-md-10">
        <div class="shadow hero bg-white text-dark rounded-3">
            <div class="hero-inner">
                <div class="row">
                    <div class="col-10 col-sm-10 col-md-10">
                        <h5 class="text-left" style="color:#595757;">Parts Scanned</h5>
                        <div class="bg-secondary m-auto" style="max-height: 7rem; width: 100%; border-radius: 6px;">
                            <div class="list-group mt-3" style="max-height: 7rem; width: 100%; overflow:scroll; overflow-x: hidden">
                                <li class="list-group-item">
                                    <h4 style="color: #ffffff">081920192819281</h4>
                                </li>
                                <li class="list-group-item">
                                    <h4 style="color: #ffffff">081920192819281</h4>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 col-sm-2 col-md-2">
                        <h5 class="text-center mb-3" style="color:#595757;">Kanban</h5>
                        <div class="bg-primary m-auto" style="height: 7rem; width: 100%; background-color:#EAEEED; border-radius: 6px; padding: 35px 0">
                            <h1 class="text-center" style="color: #ffffff;" id="kanban-scanned">DI01</h1>         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-2 col-sm-2 col-md-2">
        <div class="shadow pt-4"  style="height: 7rem; width: 100%; background-color: #ffffff; border-radius: 6px">
            <div class="hero-inner">
                <h5 class="text-center"  style="color:#595757;">Total Scan</h5>
                <div class="bg-primary m-auto shadow" style="height: 13rem; width: 85%; border-radius: 6px; padding: 80px 0">
                    <h1 class="text-center" style="color: #ffffff;" id="total-scan">0</h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
    <script src="text/javascript">
        
    </script>
@endsection