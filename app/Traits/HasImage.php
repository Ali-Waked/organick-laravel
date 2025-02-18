<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    private $defaultDisk = 'public';
    private $fieldname = 'cover_image';

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
     * Set the Filed Name
     *
     * @param string $FiledName
     */
    public function setFiledName(string $filedName): void
    {
        $this->fieldname = $filedName;
    }

    /**
     * Get the Filed name can be used.
     *
     * @param string|null $filedName
     * @return string
     */
    protected function getFiledName(?string $filedName = null): string
    {
        return $filedName ?? $this->fieldname;
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
    public function uploadImage(?UploadedFile $image = null, string $folder, ?string $disk = null): ?string
    {
        if ($image) {
            $disk = $this->getDisk($disk);
            $path = $image->store($folder, $disk);
            return $path;
        }
        return null;
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

        return Storage::disk($disk)->url('default-image.jpg');
    }
}
