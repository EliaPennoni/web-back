@extends('layouts.app')

@section('main-content')
    <h1>Tutte le Foto</h1>
    <p>
        @if ($photoCount > 0)
            Hai caricato un totale di {{ $photoCount }} foto.
        @else
            Non ci sono foto caricate.
        @endif
    </p>
    <div class="row">
        @foreach ($photos as $photo)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="foto"
                        class="card-img-top"style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $photo->trip }}</h5>
                        <p class="card-category">{{ $photo->category ?? 'categoria non specificata' }}</p>
                        <form action="{{ route('photos.delete', $photo->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">elimina</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
