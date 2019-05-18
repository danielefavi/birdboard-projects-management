@extends('layouts.app')

@section('content')
<div class="container">
    <div class="lg:w-1/2 lg:mx-auto p-6 md:py-12 md:px-16">
        <div class="col-md-8">
            <div class="card">
                <h1 class="text-2xl font-normal mb-10 text-center">
                    {{ __('Login') }}
                </h1>


                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="field mb-6">
                            <label class="label text-sm mb-2 block" for="email">{{ __('E-Mail Address') }}</label>

                            <div class="control">
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="field mb-6">
                            <label class="label text-sm mb-2 block" for="password">Password</label>

                            <div class="col-md-6">
                                <input
                                    id="password"
                                    type="password"
                                    class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0 mt-5">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="button">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
