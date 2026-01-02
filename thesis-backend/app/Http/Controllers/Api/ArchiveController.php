<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     * Replaces: supabase.from('archives').select('*')
     */
    public function index()
    {
        // Returns all archives, ordered by newest first
        return response()->json(Archive::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     * Replaces: supabase.storage.upload() AND supabase.from('archives').insert()
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'year' => 'required|integer',
            'file' => 'required|file|mimes:pdf|max:20480', // Max 20MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // 2. Generate a clean, unique filename
            // This preserves the original name but adds a timestamp to prevent overwrites.
            // Example: 1704205000_my_thesis_paper.pdf
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            // 3. Upload to the 'public' disk in a folder named 'thesis-files'
            // Using 'storeAs' allows us to define the specific filename.
            $relativePath = $file->storeAs('thesis-files', $filename, 'public');

            // 4. Generate the Full Public URL
            // This converts "thesis-files/abc.pdf" -> "http://localhost:8000/storage/thesis-files/abc.pdf"
            // NOTE: Ensure you ran 'php artisan storage:link'
            $fullUrl = asset('storage/' . $relativePath);

            // 5. Save the record to the MySQL database
            $archive = Archive::create([
                'title' => $request->title,
                'author' => $request->author,
                'year' => $request->year,
                'file_path' => $fullUrl, // We save the full URL so the frontend can just link to it
            ]);

            return response()->json($archive, 201);
        }

        return response()->json(['error' => 'File upload failed'], 500);
    }
}