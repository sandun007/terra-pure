<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploader extends Controller
{
    /**
     * Common function to upload an image using storeAs.
     * Handles both single files and arrays of files.
     *
     * @param array|\Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string|null $filename
     * @param string $disk
     * @return string|array|null  The stored file path or array of paths or null on failure
     */
    public static function uploadImage(array|UploadedFile $file, string $path = 'uploads/images', string $filename = null, $disk = 'public')
    {
        // Handle array of files
        if (is_array($file)) {
            return self::uploadMultipleImages($file, $path, $disk);
        }

        // Handle single file
        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return null;
        }

        // Generate unique filename if not provided
        $filename = $filename ?? self::generateFilename($file);

        // Store the file using storeAs
        try {
            $stored = $file->storeAs($path, $filename, $disk);
            return $stored; // returns the full path (e.g., uploads/images/filename.jpg)
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload multiple images
     *
     * @param array $files
     * @param string $path
     * @param string $disk
     * @return array|null
     */
    private static function uploadMultipleImages(array $files, $path = 'uploads/images', $disk = 'public')
    {
        $uploadedPaths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $filename = self::generateFilename($file);
                $stored = $file->storeAs($path, $filename, $disk);

                if ($stored) {
                    $uploadedPaths[] = $stored;
                }
            }
        }

        return !empty($uploadedPaths) ? $uploadedPaths : null;
    }

    /**
     * Alternative method for single file upload with stricter type hinting
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string|null $filename
     * @param string $disk
     * @return string|null
     */
    public static function uploadSingleImage(UploadedFile $file, $path = 'uploads/images', $filename = null, $disk = 'public')
    {
        if (!$file->isValid()) {
            return null;
        }

        $filename = $filename ?? self::generateFilename($file);

        try {
            return $file->storeAs($path, $filename, $disk);
        } catch (\Exception $e) {
            \Log::error('Single image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate a unique filename for the uploaded file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private static function generateFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($baseName);

        return time() . '_' . uniqid('', true) . '_' . $safeName . '.' . $extension;
    }

    /**
     * Delete an uploaded image
     *
     * @param string $filePath
     * @param string $disk
     * @return bool
     */
    public static function deleteImage($filePath, $disk = 'public')
    {
        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }
        return false;
    }

    /**
     * Get the full URL for an uploaded image
     *
     * @param string $filePath
     * @param string $disk
     * @return string|null
     */
    public static function getImageUrl($filePath, $disk = 'public')
    {
        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->url($filePath);
        }
        return null;
    }

    /**
     * Validate image file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param array $allowedExtensions
     * @param int $maxSizeInKB
     * @return bool
     */
    public static function validateImage(UploadedFile $file, $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'], $maxSizeInKB = 2048)
    {
        if (!$file->isValid()) {
            return false;
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $sizeInKB = $file->getSize() / 1024;

        return in_array($extension, $allowedExtensions) && $sizeInKB <= $maxSizeInKB;
    }
}
