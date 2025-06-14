@extends("main")
 
@section("menu")
<a href="/internal-events/create" class="btn btn-primary"> Create new </a>
<a href="/internal-events" class="btn btn-primary"> Eventy </a>
<a href="/attachments" class="btn btn-primary"> Zalączniki </a>
@endsection
 
@section("content")
<div class="container">
<div class="row gy-3">
    @foreach($models as $model)
    <div class="card">
        <div class="card-body">
            <p class="card-title h5">{{$model->Title}}</p>
            <p><strong>{{$model->ShortDescription}}</strong></p>
            {!! $model->ContentHTML !!}

            {{-- Załączniki powiązane z eventem --}}
            @php
                $activeAttachments = $model->attachments->where('IsActive', true);
            @endphp
            @if($activeAttachments->count())
                <div class="mt-3">
                    <strong>Załączniki:</strong>
                    <ul>
                        @foreach($activeAttachments as $attachment)
                            <li>
                                {{ $attachment->Title }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{url()->current()}}/edit/{{$model->Id}}">Edit</a>
            <a href="{{url()->current()}}/delete/{{$model->Id}}">Delete</a>
        </div>
    </div>
    @endforeach
</div>
</div>
@endsection