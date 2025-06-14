@extends('main')

@section('title', 'Dashboard')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="row">
  @include('home.leftSide')
  @include('home.mid')
  @include('home.rightSide')
</div>
@endsection