@extends('layouts.app')

@section('content')
<div class="container">
    <div class="lg:w-1/2 lg:mx-auto p-6 md:py-12 md:px-16">
        <div class="col-md-8">
            <div class="card">
                <h1 class="text-2xl font-normal mb-10 text-center">
                    {{ __('Register') }}
                </h1>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="field mb-6">
                            <label for="name" class="label text-sm mb-2 block">{{ __('Name') }}</label>

                            <div class="control">
                                <input
                                    id="name"
                                    type="text"
                                    class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="field mb-6">
                            <label for="email" class="label text-sm mb-2 block">{{ __('E-Mail Address') }}</label>

                            <div class="control">
                                <input
                                    id="email"
                                    type="email"
                                    class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="field mb-6">
                            <label for="password" class="label text-sm mb-2 block">{{ __('Password') }}</label>

                            <div class="control">
                                <input
                                    id="password"
                                    type="password"
                                    class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password"
                                    required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="field mb-6">
                            <label for="password-confirm" class="label text-sm mb-2 block">{{ __('Confirm Password') }}</label>

                            <div class="control">
                                <input
                                    id="password-confirm"
                                    type="password"
                                    class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full"
                                    name="password_confirmation"
                                    required>
                            </div>
                        </div>

                        <div class="field mb-6">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="button">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
