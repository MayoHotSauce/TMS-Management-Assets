@extends('adminlte::master')

@section('title', 'TMS Management')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Silakan Login untuk Akses Dashboard</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="member_id">Member ID</label>
                        <input type="text" name="member_id" id="member_id" required>
                    </div>

                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    @error('member_id')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <button type="submit">Login</button>
                </form>

                @if (Route::has('password.request'))
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">I forgot my password</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop