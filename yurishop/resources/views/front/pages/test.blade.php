

@extends('layouts.master')
@section('title','Test')
@section('content')

@include('notifications.status_message') @include('notifications.errors_message') @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
            @endif @if (session()->has('error_message'))
            <div class="alert alert-danger">
                {{ session()->get('error_message') }}
            </div>
            @endif
  
@endsection
