@extends('layouts.root.auth')

@section('main')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <h3 class="text-dark text-center mb-3 mt-5">ELECTRIC</h3>
                    
                    <div class="card card-primary">
                        <div class="card-header"><h4>Login</h4></div>
                        
                        <div class="card-body">
                            <form method="POST" action="{{ route('login.auth') }}" class="needs-validation" novalidate="">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="npk">NPK</label>
                                    <input id="npk" type="string" class="form-control" name="npk" tabindex="1" required autofocus>
                                    <div class="invalid-feedback">
                                        Please fill in your npk
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password</label>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                    <div class="invalid-feedback">
                                        please fill in your password
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                                        <label class="custom-control-label" for="remember-me">Remember Me</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3 text-muted text-center">
                        Don't have an account? <a href="{{ route('register.index') }}">Create One</a>
                    </div>
                    <div class="simple-footer">
                        Copyright &copy; ITD 2023
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection