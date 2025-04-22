<?php

namespace App\Traits;


use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    /**
     * Lưu file vào storage.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     */
//    public function uploadFile(UploadedFile $file, string $folder): ?string
//    {
//        return $file->store($folder, 'public');
//    }
    public function uploadFile($file, $folder = 'products')
    {
        if (!$file) {
            throw new \Exception("No file provided or file is null.");
        }

        // Upload file lên Cloudinary thông qua Storage
        $path = Storage::disk('cloudinary')->putFile($folder, $file);

        // Trả về URL đầy đủ từ Cloudinary
        return Storage::disk('cloudinary')->url($path);
    }

    /**
     * Xóa file khỏi storage.
     *
     * @param string|null $filePath
     * @return void
     */
    public function deleteFile(?string $filePath): void
    {
        if ($filePath) {
            // Xoá theo public_id
            Cloudinary::destroy($filePath);
        }
    }
}
