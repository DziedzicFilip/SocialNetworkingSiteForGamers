
@extends('main')

@section('title', 'My Teams')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')


<div class="container py-4">
    <h2 class="mb-4 text-primary">My Teams</h2>

    <!-- Filtry -->
    <form class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="gameFilter" class="form-label">Game</label>
                <select id="gameFilter" class="form-select">
                    <option selected>All Games</option>
                    <option>League of Legends</option>
                    <option>Counter-Strike 2</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="roleFilter" class="form-label">Role</label>
                <select id="roleFilter" class="form-select">
                    <option selected>All Roles</option>
                    <option>Leader</option>
                    <option>Member</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Example: League of Legends -->
    <div class="card mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <img src="{{ asset('IMG/lol.jpg') }}" alt="League of Legends" class="me-3" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
            <h5 class="mb-0">League of Legends</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Arcade Warriors</h5>
                        <div class="text-muted mb-2">Competitive team focused on ranked tournaments.</div>
                        <div>
                            <strong>Members:</strong>
                            <span class="badge bg-secondary">PlayerOne</span>
                            <span class="badge bg-secondary">GamerGirl</span>
                            <span class="badge bg-secondary">NoobMaster</span>
                            <span class="badge bg-secondary">ProKiller</span>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary">Leader</span>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Next Match:</strong>
                    <span class="badge bg-success">vs DragonSlayers - 2025-06-20 18:00</span>
                </div>
                <a href="{{ route('teams.details', ['id' => 1]) }}" class="btn btn-outline-primary btn-sm ms-2">Details</a>
            </div>
            <hr>
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Night Owls</h5>
                        <div class="text-muted mb-2">Casual team for night gaming sessions.</div>
                        <div>
                            <strong>Members:</strong>
                            <span class="badge bg-secondary">NightWolf</span>
                            <span class="badge bg-secondary">Shadow</span>
                            <span class="badge bg-secondary">Moonlight</span>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary">Member</span>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Next Match:</strong>
                    <span class="text-muted">No upcoming matches</span>
                </div>
            </div>
            <a href="{{ route('teams.details', ['id' => 1]) }}" class="btn btn-outline-primary btn-sm ms-2">Details</a>
        </div>
        
    </div>

    <!-- Example: Counter-Strike 2 -->
    <div class="card mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <img src="{{ asset('IMG/cs2.jpg') }}" alt="Counter-Strike 2" class="me-3" style="width:48px; height:48px; object-fit:cover; border-radius:8px;">
            <h5 class="mb-0">Counter-Strike 2</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Bomb Defusers</h5>
                        <div class="text-muted mb-2">Team focused on tactical gameplay and tournaments.</div>
                        <div>
                            <strong>Members:</strong>
                            <span class="badge bg-secondary">FlashBang</span>
                            <span class="badge bg-secondary">SmokeMaster</span>
                            <span class="badge bg-secondary">EagleEye</span>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary">Member</span>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Next Match:</strong>
                    <span class="badge bg-success">vs HeadshotCrew - 2025-06-22 20:30</span>
                </div>
            </div>
            <a href="{{ route('teams.details', ['id' => 1]) }}" class="btn btn-outline-primary btn-sm ms-2">Details</a>
        </div>
    </div>
    
</div>
@endsection