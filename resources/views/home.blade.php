@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <router-view :userLogged="{{ auth()->user()->load(['roles','roles.permissions']) }}" appName="{{ config('app.name', 'Laravel') }}"></router-view>
@endsection
