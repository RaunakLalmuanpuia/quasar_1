<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $media = Media::paginate(4);
        return Inertia::render('Media/Create',[
            'medias' => $media,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'title' => 'required|string',
            'about' => 'required|string',
            'video_id' => 'required|string',
            'image' => 'required|image'
        ]);

        $file = $request->file('image');
        $filepath = $file->store('public/image');
        $filepath = str_replace('public/', '', $filepath); // Remove 'public/' from the path
        $media = new Media([
            'title' => $request->title,
            'about' => $request->about,
            'video_id' => $request->video_id,
            'image' => $filepath
        ]);
        $media->save();
        return redirect()->route('media.create')->with('message', 'Media Uploaded Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        dd($media);
        
        return Inertia::render('Media/Show', [
            'media' => $media
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        // dd($media);
        $media->delete();
        return redirect()->route('media.index')->with('message', 'Media Deleted');
    }
}
