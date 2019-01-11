@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    @if(Auth::guard('api')->check())
                        {{ Auth::guard('api')->user()->name }} - Messenger
                    @endif
                </div>

                <div class="card-body" id="app">
                    @if(Auth::guard('api')->check())
                        <chat-app :user="{{ Auth::guard('api')->user() }}"></chat-app>
                    @else
                        <h2>You are not authorized</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
