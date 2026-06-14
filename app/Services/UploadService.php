<?php

namespace App\Services;

use Cloudinary\Cloudinary;

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

        if (
            !empty($_ENV['CLOUDINARY_CLOUD_NAME']) &&
            !empty($_ENV['CLOUDINARY_API_KEY']) &&
            !empty($_ENV['CLOUDINARY_API_SECRET'])
        ) {
            return self::uploadToCloudinary($file, $destinationDir);
        }

        return self::uploadToLocal($file, $destinationDir, $ext);
    }

    private static function uploadToCloudinary(array $file, string $destinationDir): string
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        $folder = 'beauty-care';

        if (str_contains($destinationDir, 'payments')) {
            $folder = 'beauty-care/payments';
        }

        if (str_contains($destinationDir, 'products')) {
            $folder = 'beauty-care/products';
        }

        $result = $cloudinary->uploadApi()->upload($file['tmp_name'], [
            'folder' => $folder,
            'resource_type' => 'image',
        ]);

        return $result['secure_url'];
    }

    private static function uploadToLocal(array $file, string $destinationDir, string $ext): string
    {
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