<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    protected $defaultDisk = 'public';

    /**
     * Set the default disk.
     *
     * @param string $disk
     */
    public function setDefaultDisk(string $disk): void
    {
        $this->defaultDisk = $disk;
    }

    /**
     * Get the disk to use.
     *
     * @param string|null $disk
     * @return string
     */
    protected function getDisk(?string $disk = null): string
    {
        return $disk ?? $this->defaultDisk;
    }

    /**
     * Upload an image to the specified folder and disk.
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $folder
     * @param string|null $disk
     * @param string|null $existingImage
     * @return string|null
     */
    public function uploadImage(UploadedFile $image, string $folder, ?string $disk = null): string
    {
        $disk = $this->getDisk($disk);

        // Store the new image in the specified folder
        $path = $image->store($folder, $disk);

        return $path; // Return the image path
    }

    /**
     * Remove an image from the specified disk.
     *
     * @param string $imagePath
     * @param string|null $disk
     * @return bool
     */
    public function removeImage(string $imagePath, ?string $disk = null): bool
    {
        $disk = $this->getDisk($disk);

        if (Storage::disk($disk)->exists($imagePath)) {
            return Storage::disk($disk)->delete($imagePath);
        }

        return false;
    }

    /**
     * Get the URL of the image.
     *
     * @param string $imagePath
     * @param string|null $disk
     * @return string
     */
    public function getImageUrl(?string $imagePath, ?string $disk = null): string
    {
        $disk = $this->getDisk($disk);
        if (Storage::disk($disk)->exists($imagePath)) {
            return Storage::disk($disk)->url($imagePath);
        }

        return Storage::disk($disk)->url('default-image.jpg'); // Fallback image URL
    }
}
