{{-- resources/views/attachment/edit.blade.php --}}
@extends('main')

@section('menu')
<a href="/attachments/create" class="btn btn-primary">Create new</a>
<a href="/attachments" class="btn btn-primary">Załączniki</a>
@endsection

@section('content')
<div class="container mt-4">
  <h2>Edytuj załącznik</h2>

  <form method="POST" action="/attachments/{{ $model->Id }}/update">
    @csrf

    <div class="mb-3">
      <label for="Title">Tytuł</label>
      <input type="text" id="Title" name="Title" class="form-control" value="{{ $model->Title }}">
    </div>

    <div class="mb-3">
      <label for="Link">Link</label>
      <input type="text" id="Link" name="Link" class="form-control" value="{{ $model->Link }}">
    </div>

    <div class="mb-3">
      <label for="ContentHTML">Zawartość (HTML)</label>
      <textarea id="ContentHTML" name="ContentHTML" class="form-control" rows="6">{{ $model->ContentHTML }}</textarea>
    </div>

    <div class="mb-3">
      <label for="Notes">Notatki</label>
      <textarea id="Notes" name="Notes" class="form-control" rows="2">{{ $model->Notes }}</textarea>
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" id="IsActive" name="IsActive" {{ $model->IsActive ? 'checked' : '' }}>
      <label for="IsActive" class="form-check-label">Aktywny</label>
    </div>

    <button type="submit" class="btn btn-primary">Zapisz</button>
    <a href="/attachments" class="btn btn-secondary">Anuluj</a>
  </form>
</div>
@endsection
