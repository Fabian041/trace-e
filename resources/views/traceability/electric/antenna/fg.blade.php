@extends('layouts.root.auth')

@section('main')    
<div class="main-section mt-5">
    <section class="section">
        <div class="row">
            <div class="col-10 col-sm-10 offset-sm-2 col-md-10 offset-md-1">
                <div class="shadow hero bg-white text-dark rounded-3">
                    <div class="hero-inner">
                        <input id="code" type="text" class="form-control" name="code" tabindex="1" placeholder="scan part here..." required autofocus>

                        <div class="row mt-3">
                            <div class="col-md-2 mt-4">
                                <h1 class="text-dark">ASAN01</h1>
                            </div>
                            <div class="col-md-10">
                                <div class="hero bg-info text-dark">
                                    <div class="hero-inner">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection