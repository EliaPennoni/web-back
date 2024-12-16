<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Log;

class PhotosController extends Controller
{
    // Visualizza tutte le foto
    public function index()
    {
        $photos = Photo::all();
        $photoCount = $photos->count();

        return view('photos.index', compact('photos', 'photoCount'));
    }

    // Carica nuove foto
    public function store(Request $request)
    {
        Log::info('Start uploading photos', ['trip' => $request->trip, 'category' => $request->category]);

        // Validazione dei dati
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20240', // Max 10MB per immagine
            'trip' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        // Verifica il numero di file caricati
        Log::info('Number of photos uploaded', ['count' => count($request->file('photos'))]);

        foreach ($request->file('photos') as $photoFile) {
            Log::info('Processing photo file', ['file_name' => $photoFile->getClientOriginalName()]);

            // Controlla se il file è una vera immagine
            if (!$photoFile->isValid()) {
                Log::error('Invalid image file', ['file_name' => $photoFile->getClientOriginalName()]);
                return back()->with('error', 'Il file caricato non è valido.');
            }

            // Apri l'immagine con la libreria GD
            $image = imagecreatefromjpeg($photoFile); // Per JPEG, puoi cambiare a png, gif, ecc.

            // Verifica se l'immagine è stata caricata correttamente
            if (!$image) {
                Log::error('Failed to create image from file', ['file' => $photoFile->getClientOriginalName()]);
                return back()->with('error', 'Impossibile aprire il file immagine.');
            }

            // Ridimensiona l'immagine
            $width = imagesx($image);
            $height = imagesy($image);
            $newWidth = 1200;
            $newHeight = (int) ($height * ($newWidth / $width));

            Log::info('Resizing image', ['original_width' => $width, 'original_height' => $height, 'new_width' => $newWidth, 'new_height' => $newHeight]);

            // Crea una nuova immagine ridimensionata
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            $path = 'photos/' . time() . '.jpg'; // Cambia percorso se necessario
            $fullPath = storage_path('app/public/' . $path);

            $photosDir = storage_path('app/public/photos');
            if (!file_exists($photosDir)) {
                mkdir($photosDir, 0775, true); // Crea la cartella con permessi di scrittura
                Log::info('Created photos directory', ['path' => $photosDir]);
            }

            Log::info('Saving image', ['path' => $fullPath]);

            // Salva l'immagine ridimensionata
            if (!imagejpeg($newImage, $fullPath, 90)) {
                Log::error('Failed to save image', ['path' => $fullPath]);
                return back()->with('error', 'Errore nel salvataggio dell\'immagine.');
            }

            // Salva i dettagli nel database
            $photo = new Photo();
            $photo->path = $path;
            $photo->trip = $request->trip;
            $photo->category = $request->category ?? 'general';
            $photo->save();

            // Distruggi le risorse
            imagedestroy($image);
            imagedestroy($newImage);

            Log::info('Photo uploaded successfully', ['photo_id' => $photo->id]);
        }

        // Ritorna alla pagina precedente con il messaggio di successo
        return back()->with('success', 'Le foto sono state caricate con successo');
    }

    // Elimina una foto
    public function delete($id)
    {
        $photo = Photo::findOrFail($id);

        // Verifica se il file esiste prima di eliminarlo
        $filePath = storage_path('app/public/' . $photo->path);
        Log::info('Deleting photo', ['file_path' => $filePath]);

        if (file_exists($filePath)) {
            unlink($filePath); // Elimina il file fisicamente
            $photo->delete(); // Elimina dal database
            Log::info('Photo deleted successfully', ['photo_id' => $photo->id]);

            return back()->with('success', 'La tua immagine è stata correttamente eliminata');
        } else {
            Log::error('File not found for deletion', ['file_path' => $filePath]);

            return back()->with('error', 'Il file non esiste più o non può essere trovato');
        }
    }
}
