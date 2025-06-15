
@extends('main')

@section('title', 'Edit Profile')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 mb-4 shadow-sm">
                <h2 class="mb-4 text-primary">Profile</h2>
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="card p-4 mb-4 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>
            <div class="card p-4 mb-4 shadow-sm">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection