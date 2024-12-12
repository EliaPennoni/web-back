@extends('layouts.app')

@section('main-content')
    <h1>
        Carica le tue immagini
    </h1>
    <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" class="photo-form">
        @csrf

        <div style="display: flex; flex-direction: column;">
            <label for="trip" style="margin-bottom: 5px;">Luogo del Viaggio:</label>
            <input type="text" name="trip" id="trip" style="width: 100%;" required>
        </div>

        <!-- Etichetta e input per 'Categoria' -->
        <div style="display: flex; flex-direction: column;">
            <label for="category" style="margin-bottom: 5px;">Categoria (opzionale):</label>
            <input type="text" name="category" id="category" style="width: 100%;" required>
        </div>

        <!-- Etichetta e input per 'Carica Foto' -->
        <div style="display: flex; flex-direction: column;">
            <label for="photos" style="margin-bottom: 5px;">Carica Foto (puoi selezionare più immagini):</label>
            <input type="file" name="photos[]" id="photos" multiple style="width: 100%;">
        </div>

        <button type="submit" style="align-self: flex-start;">Invia</button>
    </form>
@endsection
