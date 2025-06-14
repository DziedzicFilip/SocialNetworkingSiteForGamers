@extends('main')
 
@section('menu')
<a href="/attachments/create" class="btn btn-primary">Create new</a>
<a href="/attachments" class="btn btn-primary">Załączniki</a>
<a href="/internal-events" class="btn btn-primary"> Eventy </a>
 
@endsection
 
@section('content')
<div class="container">
  <div class="row gy-3">
    @foreach($models as $model)
      <div class="card">
        <div class="card-body">
          <p class="card-title h5">{{ $model->Title }}</p>
          <p><strong>{{ \Illuminate\Support\Str::limit(strip_tags($model->ContentHTML), 80) }}</strong></p>
          {!! $model->ContentHTML !!}
        </div>
        <div class="card-footer">
          <a href="{{ url()->current() }}/edit/{{ $model->Id }}">Edit</a>
          <a href="{{ url()->current() }}/delete/{{ $model->Id }}">Delete</a>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection