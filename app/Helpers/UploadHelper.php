<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class UploadHelper
{
    /**
     * Resolve full web URL for any uploaded file type with fallback default assets.
     */
    public static function url(string $type, ?string $filename, ?string $gender = null): string
    {
        $filename = trim((string) $filename);

        // Check empty or legacy "no file" placeholder
        if ($filename === '' || strtolower($filename) === 'no file' || strtolower($filename) === 'default') {
            return self::getDefaultFallbackUrl($type, $gender);
        }

        // Check if full URL or data URI
        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://') || str_starts_with($filename, 'data:')) {
            return $filename;
        }

        // Check if path already includes 'uploads/'
        if (str_starts_with($filename, 'uploads/')) {
            $path = public_path($filename);
            if (File::exists($path)) {
                return asset($filename);
            }
            return self::getDefaultFallbackUrl($type, $gender);
        }

        // Standard relative filename (e.g. "profile_1528205632.jpg")
        $relativePath = 'uploads/' . trim($type, '/') . '/' . ltrim($filename, '/');
        $fullPath = public_path($relativePath);

        if (File::exists($fullPath)) {
            return asset($relativePath);
        }

        return self::getDefaultFallbackUrl($type, $gender);
    }

    /**
     * Get default fallback image URL per type.
     */
    protected static function getDefaultFallbackUrl(string $type, ?string $gender = null): string
    {
        return match ($type) {
            'profile', 'users' => strtolower((string) $gender) === 'female'
                ? asset('uploads/profile/default_female.jpg')
                : asset('uploads/profile/default_male.jpg'),
            'logo' => asset('uploads/logo/logo_1538139736.jpg'),
            'asset_image' => asset('assets/images/default_asset.png'),
            'award' => asset('assets/images/default_award.png'),
            default => asset('assets/images/default_file.png'),
        };
    }

    /**
     * Store uploaded file into public/uploads/{type} using legacy naming convention.
     */
    public static function upload(UploadedFile $file, string $type, ?string $prefix = null): string
    {
        $targetDir = public_path('uploads/' . trim($type, '/'));

        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $prefixName = $prefix ? preg_replace('/[^a-zA-Z0-9_]/', '', $prefix) : $type;
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $generatedName = $prefixName . '_' . time() . '_' . substr(md5(uniqid()), 0, 6) . '.' . $extension;

        $file->move($targetDir, $generatedName);

        return $generatedName;
    }

    /**
     * Safely delete a file from public/uploads/{type}.
     */
    public static function delete(string $type, ?string $filename): bool
    {
        if (empty($filename) || str_contains($filename, 'default')) {
            return false;
        }

        $cleanFilename = str_replace('uploads/' . $type . '/', '', $filename);
        $fullPath = public_path('uploads/' . trim($type, '/') . '/' . ltrim($cleanFilename, '/'));

        if (File::exists($fullPath)) {
            return File::delete($fullPath);
        }

        return false;
    }
}
