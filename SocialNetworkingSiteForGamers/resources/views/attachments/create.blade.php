{{-- resources/views/attachment/create.blade.php --}}
@extends('main')

@section('menu')
<a href="/attachments/create" class="btn btn-primary">Create new</a>
<a href="/attachments" class="btn btn-primary">Załączniki</a>
@endsection

@section('content')
<div class="container mt-4">
  <h2>Dodaj załącznik</h2>

  <form method="POST" action="/attachments/add-to-db">
    @csrf

    <div class="mb-3">
      <label for="Title">Tytuł</label>
      <input type="text" id="Title" name="Title" class="form-control" value="{{ old('Title', $model->Title) }}">
    </div>

    <div class="mb-3">
      <label for="Link">Link</label>
      <input type="text" id="Link" name="Link" class="form-control" value="{{ old('Link', $model->Link) }}">
    </div>

    <div class="mb-3">
      <label for="ContentHTML">Zawartość (HTML)</label>
      <textarea id="ContentHTML" name="ContentHTML" class="form-control" rows="6">{{ old('ContentHTML', $model->ContentHTML) }}</textarea>
    </div>

    <div class="mb-3">
      <label for="Notes">Notatki</label>
      <textarea id="Notes" name="Notes" class="form-control" rows="2">{{ old('Notes', $model->Notes) }}</textarea>
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" id="IsActive" name="IsActive" {{ old('IsActive', $model->IsActive) ? 'checked' : '' }}>
      <label for="IsActive" class="form-check-label">Aktywny</label>
    </div>

    <div class="mb-3">
      <label for="internal_events">Powiąż z wydarzeniem</label>
      <select class="form-select" id="internal_events" name="internal_events[]" multiple>
        @foreach(\App\Models\InternalEvent::all() as $event)
          <option value="{{ $event->Id }}">{{ $event->Title }}</option>
        @endforeach
      </select>
      <small class="form-text text-muted">Przytrzymaj Ctrl, aby wybrać wiele.</small>
    </div>

    <button type="submit" class="btn btn-primary">Zapisz</button>
    <a href="/attachments" class="btn btn-secondary">Anuluj</a>
  </form>
</div>
@endsection
