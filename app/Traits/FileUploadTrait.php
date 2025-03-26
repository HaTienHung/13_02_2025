<?php

namespace App\Traits;

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
    public function uploadFile(UploadedFile $file, string $folder): ?string
    {
        return $file->store($folder, 'public');
    }

    /**
     * Xóa file khỏi storage.
     *
     * @param string|null $filePath
     * @return void
     */
    public function deleteFile(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
}
