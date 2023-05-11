@extends('layouts.root.traceability')

@section('main')
    <div class="row mt-5">
        <div class="col-12 col-sm-12 col-md-12">
            <h5 class="mb-3 text-right"><span class="badge badge-dark" style="border-radius: 7px !important">Welcome,
                    {{ auth()->user()->name }}</span></h5>
            <div class="shadow hero bg-white text-dark rounded-3">
                <div class="hero-inner">
                    <div class="row mb-3 w-100">
                        <div class="col-12 d-flex justify-content-center w-100">
                            <h1 class="text-primary" style="font-weight: 800 !impoertant">Input Part NG - LINE AS001</h1>
                        </div>
                    </div>
                    <div class="row mb-3 w-100">
                        <div class="col-12 d-flex justify-content-center w-100">
                            <h1 class="text-danger" style="font-weight: 400 !impoertant">Part Rusak</h1>
                        </div>
                    </div>

                    <form class="row mb-3 w-100">
                        <input id="code" type="text" class="form-control" name="code" tabindex="1"
                            placeholder="scan part here..." required autofocus>
                    </form>
                    <div class="row mb-3 w-100 ">
                        <h5 class="text-left text-primary">Parts Scanned | Rusak</h5>
                        <div class=" col-12 list-group mt-3" style="max-height: 7rem; width: 100%; overflow-y: scroll; overflow-x: hidden;" >
                            <li class="list-group-item">
                                <h4 class="text-dark">081920192819281</h4>
                            </li>
                            <li class="list-group-item">
                                <h4 class="text-dark">081920192819281</h4>
                            </li>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
@endsection

@section('custom-script')
    <script src="text/javascript"></script>
@endsection
