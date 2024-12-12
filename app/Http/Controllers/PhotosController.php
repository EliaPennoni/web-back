<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PhotosController extends Controller
{
    public function index()
    {
        $photos = Photo::all();

        $photoCount = $photos->count();

        return view('photos.index', compact('photos', 'photoCount'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'trip' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        foreach ($request->file('photos') as $photoFile) {
            $path = $photoFile->store('photos', 'public');

            $photo = new Photo();
            $photo->path = $path;
            $photo->trip = $request->trip;
            $photo->category = $request->category ?? 'general';
            $photo->save();
        }

        return back() - with('success', 'le foto sono state caricate con successo');
    }

    public function delete($id)
    {
        $photo = Photo::findOrFail($id);

        if (file_exists(storage_path('app/public/' . $photo->path))) {
            unlink(storage_path('app/public/' . $photo->path));

            $photo->delete();
            return back()->with('success', 'la tua immgaine e stata correttamente eliminata');
        }
    }
}
