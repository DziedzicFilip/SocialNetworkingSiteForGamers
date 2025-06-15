@extends('main')

@section('title', 'Dashboard')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-3">
      @include('home.leftSide')
    </div>
    <div class="col-md-6 main-content">
      @include('home.mid')
    </div>
    <div class="col-md-3">
      @include('home.rightSide')
    </div>
  </div>
</div>
@endsection