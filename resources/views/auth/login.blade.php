@extends('layouts.app', ['class' => 'login-page', 'page' => 'GRBASMH iHOMIS', 'contentClass' => 'login-page', 'section' => 'auth'])

@section('content')
    <div class="col-lg-4 col-md-6 ml-auto mr-auto">
        <form class="form" method="post" action="{{ route('login') }}">
            @csrf
            
            {{-- <div class="logo-container">
                <img src="../assets/img/logo.png" alt="">
            </div> --}}
            <div class="card card-login card-black">
               
                <div class="card-header card-center">
                    <img src="../assets/img/logo.png" alt="" width="100px" height="100px">
                    {{-- <img src="{{ asset('assets') }}/img/card-primary.png" alt=""> --}}
                    <h1 class="card-title">Login</h1>
                </div> 
                <div class="card-body">
                    <div class="input-group{{ $errors->has('username') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-single-02"></i>
                            </div>
                        </div>
                        <input type="text" name="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" placeholder="Enter User Name">
                        @include('alerts.feedback', ['field' => 'username'])
                    </div>
                    
                    {{-- <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-email-85"></i>
                            </div>
                        </div>
                        <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email">
                        @include('alerts.feedback', ['field' => 'email'])
                    </div> --}}
                    <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="tim-icons icon-lock-circle"></i>
                            </div>
                        </div>
                        <input type="password" placeholder="Password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                        @include('alerts.feedback', ['field' => 'password'])
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" href="" class="btn btn-primary btn-lg btn-block mb-3">Log in</button>
                    <div class="pull-left">
                        <h6>
                            <a href="{{ route('register') }}" class="link footer-link">Create Account</a>
                        </h6>
                    </div>
                    <div class="pull-right">
                        <h6>
                            <a href="{{ route('password.request') }}" class="link footer-link">I forgot the passwod</a>
                        </h6>
                    </div>
                </div>
            </div>
        </form>
        <div class="container">
            <div class="links"><a href="#">About me </a><a href="#">Contact me </a><a href="#">Projects</a></div>
            <div class="social-icons"><a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i class="icon ion-social-instagram-outline"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a></div>
        </div>
    </div>
    
@endsection
