
@extends('main')

@section('title', 'Team Details')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .player-card {
        background: #23272f;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
        color: #fff;
        transition: box-shadow 0.2s;
    }
    .player-card:hover {
        box-shadow: 0 4px 24px rgba(79,140,255,0.25);
    }
    .profile-btn {
        margin-left: 1rem;
    }
    .profile-modal .modal-content {
        background: #181b20;
        color: #fff;
        border-radius: 14px;
    }
    .profile-modal .modal-header {
        border-bottom: 1px solid #23272f;
    }
    .profile-modal .modal-footer {
        border-top: 1px solid #23272f;
    }
    .profile-avatar {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #4f8cff;
        margin-right: 1rem;
    }
</style>
@endpush

@section('content')

<div class="container py-4">
    <h2 class="mb-4 text-primary">Arcade Warriors <small class="text-muted">(League of Legends)</small></h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <img src="{{ asset('IMG/lol.jpg') }}" alt="League of Legends" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-8">
            <h5>Description</h5>
            <p>Competitive team focused on ranked tournaments.</p>
            <h5>Statistics</h5>
            <ul>
                <li>Matches played: <strong>24</strong></li>
                <li>Wins: <strong>16</strong></li>
                <li>Losses: <strong>8</strong></li>
                <li>Win rate: <strong>66%</strong></li>
            </ul>
            <h5>Recent Matches</h5>
            <ul>
                <li>2025-06-10: <span class="text-success">Win</span> vs DragonSlayers (18:12)</li>
                <li>2025-06-05: <span class="text-danger">Loss</span> vs Night Owls (14:16)</li>
                <li>2025-05-30: <span class="text-success">Win</span> vs ProGamers (20:10)</li>
            </ul>
            <!-- Opcja opuszczenia drużyny -->
            <form method="POST" action="#" class="mt-4 d-inline">
                @csrf
                <!-- W prawdziwej aplikacji podaj właściwy route do opuszczenia drużyny -->
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to leave this team?');">
                    Leave Team
                </button>
            </form>
        </div>
    </div>

    <h4 class="mb-3 mt-5 text-primary">Team Members</h4>
    <div class="row">
        <!-- Example player cards -->
        <div class="col-md-6">
            <div class="player-card d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('IMG/avatar1.jpg') }}" alt="PlayerOne" class="profile-avatar">
                    <div>
                        <div class="fw-bold">PlayerOne</div>
                        <div class="text-muted small">Role: Leader</div>
                    </div>
                </div>
                <button class="btn btn-outline-primary profile-btn" data-bs-toggle="modal" data-bs-target="#profileModal1">View Profile</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="player-card d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('IMG/avatar2.jpg') }}" alt="GamerGirl" class="profile-avatar">
                    <div>
                        <div class="fw-bold">GamerGirl</div>
                        <div class="text-muted small">Role: Member</div>
                    </div>
                </div>
                <button class="btn btn-outline-primary profile-btn" data-bs-toggle="modal" data-bs-target="#profileModal2">View Profile</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="player-card d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('IMG/avatar3.jpg') }}" alt="NoobMaster" class="profile-avatar">
                    <div>
                        <div class="fw-bold">NoobMaster</div>
                        <div class="text-muted small">Role: Member</div>
                    </div>
                </div>
                <button class="btn btn-outline-primary profile-btn" data-bs-toggle="modal" data-bs-target="#profileModal3">View Profile</button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="player-card d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('IMG/avatar4.jpg') }}" alt="ProKiller" class="profile-avatar">
                    <div>
                        <div class="fw-bold">ProKiller</div>
                        <div class="text-muted small">Role: Member</div>
                    </div>
                </div>
                <button class="btn btn-outline-primary profile-btn" data-bs-toggle="modal" data-bs-target="#profileModal4">View Profile</button>
            </div>
        </div>
    </div>

    <!-- Profile Modals (example for 4 users) -->
    <!-- ... (modale bez zmian) ... -->

    <a href="{{ route('teams.my') }}" class="btn btn-outline-secondary mt-4">Back to My Teams</a>
</div>
@endsection