<?php
// app/Services/PhotoroomService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoroomService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('photoroom.photoroom_url');
        $this->apiKey = config('photoroom.photoroom_key');
    }

    /**
     * Remove background from the provided image using Photoroom API.
     *
     * @param UploadedFile $image
     * @return string|null The URL of the processed image, or null on failure.
     */
    public function removeBackground(UploadedFile $image): ?string
    {
        try {
            // Make the API request with multipart/form-data
            $response = Http::withHeaders([
                'Accept' => 'image/png, application/json',
                'x-api-key' => $this->apiKey,
            ])->attach(
                'image_file',
                file_get_contents($image->getRealPath()),
                $image->getClientOriginalName()
            )->post($this->apiUrl);

            // Check if the response is an image
            if ($response->successful() && strpos($response->header('Content-Type'), 'image/png') !== false) {
                // Define a unique file name
                $fileName = 'processed_images/' . uniqid() . '.png';

                // Store the image in the public disk
                Storage::disk('public')->put($fileName, $response->body());

                // Return the URL of the stored image
                return Storage::url($fileName);
            }

            // If response is JSON (e.g., error), log it
            if ($response->json()) {
                \Log::error('Photoroom API Error:', $response->json());
            }

            return null;
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Photoroom Service Exception:', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
