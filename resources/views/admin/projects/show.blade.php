@extends('layouts.app')

@section('page-title', 'Il mio progetto')

@section('main-content')
    <div class="row">
        <div class="col">
            <h1>Il mio progetto</h1>

            <ul>

                <li>
                    ID:{{ $project->id }}</th>
                </li>

                <li>
                    Titolo: {{ $project->title }}
                </li>

                <li>
                    Slug: {{ $project->slug }}
                </li>

                <li>
                    Prezzo:{{ $project->price }}
                </li>
                <li>
                    Immagine: {{ $project->image }}
                </li>
                <li>
                    Data:{{ $project->created_at }}
                </li>

            </ul>


        </div>
    </div>
@endsection
