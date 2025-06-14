@extends('main', ['title' => 'Usuń wydarzenie'])
@section('content')
<div class="container mt-4">
    <h2>Czy na pewno chcesz usunąć to wydarzenie?</h2>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $model->Title }}</h5>
            <p class="card-text"><strong>Opis:</strong> {{ $model->ShortDescription }}</p>
            <p class="card-text">{!! $model->ContentHTML !!}</p>
        </div>
    </div>
    <form method="POST" action="/internal-events/delete/{{ $model->Id }}">
        @csrf
        <button type="submit" class="btn btn-danger">Usuń</button>
        <a href="/internal-events" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection