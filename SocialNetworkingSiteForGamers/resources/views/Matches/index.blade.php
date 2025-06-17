
@extends('main')

@section('title', 'My Matches')

@push('head')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<style>
    .calendar-placeholder {
        background: #23272f;
        border-radius: 12px;
        min-height: 320px;
        color: #b0b8c1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')


<div class="container py-4">
    <h2 class="mb-4 text-primary">My Matches</h2>

    <!-- Filtry -->
  

    <!-- Kalendarz (placeholder) -->
    <div class="calendar-placeholder mb-4">
        [Calendar with your matches will be here]
    </div>
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
                <label for="statusFilter" class="form-label">Status</label>
                <select id="statusFilter" class="form-select">
                    <option selected>All</option>
                    <option>Upcoming</option>
                    <option>Finished</option>
                    <option>Canceled</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>
    <!-- Lista meczów -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Upcoming Matches</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Arcade Warriors vs DragonSlayers</strong>
                        <div class="text-muted small">2025-06-20 18:00 • League of Legends</div>
                    </div>
                    <div>
                        <a href="#" class="btn btn-outline-info btn-sm">Details</a>
                        <a href="#" class="btn btn-outline-danger btn-sm ms-2">Cancel</a>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Bomb Defusers vs HeadshotCrew</strong>
                        <div class="text-muted small">2025-06-22 20:30 • Counter-Strike 2</div>
                    </div>
                    <div>
                        <a href="#" class="btn btn-outline-info btn-sm">Details</a>
                        <a href="#" class="btn btn-outline-danger btn-sm ms-2">Cancel</a>
                    </div>
                </li>
                <!-- Dodaj kolejne mecze tutaj -->
            </ul>
        </div>
    </div>
</div>
@endsection