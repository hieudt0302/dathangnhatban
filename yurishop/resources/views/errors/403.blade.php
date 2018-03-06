@extends('layouts.master')
 
@section('content')
        <div class="container">
            <div>
                <h1 id="homeHeading">403</h1>
                <h2>Who are you?</h2>
                <hr>
                @include('notifications.status_message')
                @include('notifications.errors_message')
                <p>You don't have permission!</p>
            </div>
        </div>
@endsection