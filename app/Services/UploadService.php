<?php
namespace App\Services;

class UploadService
{
    public static function uploadImage(array $file, string $destinationDir, array $allowedExt = ['jpg', 'jpeg', 'png', 'webp']): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload file gagal.');
        }

        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            throw new \RuntimeException('Ukuran file maksimal 2MB.');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            throw new \RuntimeException('Format file tidak didukung.');
        }

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $filename = uniqid('img_', true) . '.' . $ext;
        $target = rtrim($destinationDir, '/') . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Gagal menyimpan file upload.');
        }

        return $filename;
    }

    public static function uploadMultipleImages(array $files, string $destinationDir): array
    {
        $uploaded = [];

        if (empty($files['name']) || !is_array($files['name'])) {
            return $uploaded;
        }

        foreach ($files['name'] as $index => $name) {
            if (!$name) {
                continue;
            }

            $file = [
                'name' => $files['name'][$index],
                'type' => $files['type'][$index],
                'tmp_name' => $files['tmp_name'][$index],
                'error' => $files['error'][$index],
                'size' => $files['size'][$index],
            ];

            $uploaded[] = self::uploadImage($file, $destinationDir);
        }

        return $uploaded;
    }
}