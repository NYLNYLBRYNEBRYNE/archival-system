<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Archive extends Model
{
    protected $fillable = ['title', 'author', 'year', 'file_path'];

    // This 'appends' a new field called 'file_url' to the JSON response automatically
    protected $appends = ['file_url'];

    // Define the accessor logic
    // This replaces: supabase.storage.from(...).getPublicUrl()
    public function getFileUrlAttribute()
    {
        // Generates: http://your-domain.com/storage/thesis-files/filename.pdf
        return url('storage/' . $this->file_path);
    }
}