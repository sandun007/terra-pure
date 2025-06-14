<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploader extends Controller
{
    /**
     * Common function to upload an image using storeAs.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string|null $filename
     * @param string $disk
     * @return string|null  The stored file path or null on failure
     */
    public static function uploadImage($file, $path = 'uploads/images', $filename = null, $disk = 'public')
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Generate unique filename if not provided
        $filename = $filename ?? time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store the file using storeAs
        $stored = $file->storeAs($path, $filename, $disk);

        return $stored; // returns the full path (e.g., uploads/images/filename.jpg)
    }
}
