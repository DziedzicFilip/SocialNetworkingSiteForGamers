@extends('main', ['title' => 'Dodaj wydarzenie'])
@section('content')
<div class="container mt-4">
    <h2>Dodaj wydarzenie</h2>
 
    <form method="POST" action="/internal-events/add-to-db/{{ $model->Id }}">
        @csrf
 
        <div class="mb-3">
            <label for="Title">Tytuł</label>
            <input type="text" id="Title" name="Title" class="form-control" value="{{ $model->Title }}">
        </div>
 
        <div class="mb-3">
            <label for="Link">Link</label>
            <input type="text" id="Link" name="Link" class="form-control" value="{{ $model->Link }}">
        </div>
 
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="IsPublic" name="IsPublic" {{ $model->IsPublic ? 'checked' : '' }}>
            <label class="form-check-label" for="IsPublic">Publiczne</label>
        </div>
 
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="IsCancelled" name="IsCancelled" {{ $model->IsCancelled ? 'checked' : '' }}>
            <label class="form-check-label" for="IsCancelled">Odwołane</label>
        </div>
 
        <div class="mb-3">
            <label for="EventDateTime">Data wydarzenia</label>
            <input type="datetime-local" id="EventDateTime" name="EventDateTime" class="form-control"
                value="{{ \Carbon\Carbon::parse($model->EventDateTime)->format('Y-m-d\TH:i') }}">
        </div>
 
        <div class="mb-3">
            <label for="PublishDateTime">Data publikacji</label>
            <input type="datetime-local" id="PublishDateTime" name="PublishDateTime" class="form-control"
                value="{{ \Carbon\Carbon::parse($model->PublishDateTime)->format('Y-m-d\TH:i') }}">
        </div>
 
        <div class="mb-3">
            <label for="ShortDescription">Krótki opis</label>
            <input type="text" id="ShortDescription" name="ShortDescription" class="form-control" value="{{ $model->ShortDescription }}">
        </div>
 
        <div class="mb-3">
            <label for="ContentHTML">Treść (HTML)</label>
            <textarea id="ContentHTML" name="ContentHTML" class="form-control" rows="6">{{ $model->ContentHTML }}</textarea>
        </div>
 
        <div class="mb-3">
            <label for="MetaDescription">Meta opis</label>
            <textarea id="MetaDescription" name="MetaDescription" class="form-control" rows="2">{{ $model->MetaDescription }}</textarea>
        </div>
 
        <div class="mb-3">
            <label for="MetaTags">Meta tagi</label>
            <input type="text" id="MetaTags" name="MetaTags" class="form-control" value="{{ $model->MetaTags }}">
        </div>
 
        <div class="mb-3">
            <label for="Notes">Notatki</label>
            <textarea id="Notes" name="Notes" class="form-control" rows="2">{{ $model->Notes }}</textarea>
        </div>
 
        <div class="mb-3">
            <label for="attachments">Powiąż załączniki</label>
            <select class="form-select" id="attachments" name="attachments[]" multiple>
                @foreach(\App\Models\Attachment::all() as $attachment)
                    <option value="{{ $attachment->Id }}">{{ $attachment->Title }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Przytrzymaj Ctrl, aby wybrać wiele.</small>
        </div>
 
        <button type="submit" class="btn btn-primary">Zapisz</button>
        <a href="/internal-events" class="btn btn-secondary">Anuluj</a>
    </form>
</div>
@endsection