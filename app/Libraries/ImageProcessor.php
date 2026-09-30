<?php

namespace App\Libraries;

use GdImage;
use RuntimeException;

/**
 * ImageProcessor
 *
 * Saves quiz images (uploads, canvas renders, existing files) under
 * public/assets/ and re-encodes them to PNG, JPEG or WebP with GD.
 * All methods return the web path stored in the DB, e.g. "assets/options/q1_a.webp".
 */
class ImageProcessor
{
    public const FORMATS = ['png' => 'PNG', 'jpg' => 'JPEG', 'webp' => 'WebP'];

    private const MAX_BYTES    = 5 * 1024 * 1024;
    private const JPEG_QUALITY = 90;
    private const WEBP_QUALITY = 90;

    /**
     * Saves raw image bytes (an upload or a decoded canvas) as a new file in $dir.
     * $format '' keeps the source type when it is png/jpg/webp, otherwise PNG.
     */
    public function saveBytes(string $bytes, string $dir, string $format = ''): string
    {
        if (strlen($bytes) > self::MAX_BYTES) {
            throw new RuntimeException('Image is larger than 5 MB.');
        }

        $info = @getimagesizefromstring($bytes);
        $img  = $info ? @imagecreatefromstring($bytes) : false;
        if (!$img) {
            throw new RuntimeException('The file is not a valid image.');
        }

        $format = $this->normalizeFormat($format) ?? $this->formatFromMime($info['mime']) ?? 'png';
        $name   = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3));

        return $this->write($img, trim($dir, '/') . '/' . $name . '.' . $format, $format);
    }

    /** Saves a "data:image/...;base64," URL produced by canvas.toDataURL(). */
    public function saveDataUrl(string $dataUrl, string $dir, string $format = ''): string
    {
        if (!preg_match('#^data:image/[a-z+]+;base64,#i', $dataUrl, $m)) {
            throw new RuntimeException('Canvas output is not a valid image.');
        }
        $bytes = base64_decode(substr($dataUrl, strlen($m[0])), true);
        if ($bytes === false) {
            throw new RuntimeException('Canvas output could not be decoded.');
        }

        return $this->saveBytes($bytes, $dir, $format ?: 'png');
    }

    /**
     * Converts an existing image under public/assets/ to $format, writing a
     * sibling file with the new extension (the original is kept).
     * Returns the path unchanged when it is already in that format.
     */
    public function convertExisting(string $webPath, string $format): string
    {
        $format = $this->normalizeFormat($format);
        $ext    = strtolower(pathinfo($webPath, PATHINFO_EXTENSION));
        if ($format === null || $this->normalizeFormat($ext) === $format) {
            return $webPath;
        }
        if (is_external_image($webPath)) {
            throw new RuntimeException('Format can only be changed for images stored on this site. '
                . 'Choose "Keep format", or download the image and use Upload.');
        }

        $assetsRoot = realpath(FCPATH . 'assets');
        $source     = realpath(FCPATH . ltrim($webPath, '/'));
        if (!$assetsRoot || !$source || strpos($source, $assetsRoot . DIRECTORY_SEPARATOR) !== 0) {
            throw new RuntimeException("Image not found under assets/: {$webPath}");
        }

        return $this->saveExisting($source, $webPath, $format);
    }

    private function saveExisting(string $source, string $webPath, string $format): string
    {
        $bytes = file_get_contents($source);
        $img   = $bytes !== false ? @imagecreatefromstring($bytes) : false;
        if (!$img) {
            throw new RuntimeException("Could not read image: {$webPath}");
        }

        $target = preg_replace('/\.[^.\/]+$/', '', ltrim($webPath, '/')) . '.' . $format;
        return $this->write($img, $target, $format);
    }

    private function write(GdImage $img, string $webPath, string $format): string
    {
        $abs = FCPATH . $webPath;
        if (!is_dir(dirname($abs)) && !mkdir(dirname($abs), 0775, true)) {
            throw new RuntimeException('Could not create folder for ' . $webPath);
        }

        switch ($format) {
            case 'jpg':
                // JPEG has no alpha: flatten transparent pixels onto white
                $flat = imagecreatetruecolor(imagesx($img), imagesy($img));
                imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
                imagecopy($flat, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
                $ok = imagejpeg($flat, $abs, self::JPEG_QUALITY);
                break;
            case 'webp':
                if (!function_exists('imagewebp')) {
                    throw new RuntimeException('This PHP build has no WebP support.');
                }
                $this->keepAlpha($img);
                $ok = imagewebp($img, $abs, self::WEBP_QUALITY);
                break;
            default:
                $this->keepAlpha($img);
                $ok = imagepng($img, $abs, 6);
        }

        if (!$ok) {
            throw new RuntimeException('Could not save ' . $webPath);
        }
        return $webPath;
    }

    private function keepAlpha(GdImage $img): void
    {
        if (!imageistruecolor($img)) {
            imagepalettetotruecolor($img);
        }
        imagealphablending($img, false);
        imagesavealpha($img, true);
    }

    /** Returns png|jpg|webp, or null for ''/'keep'/unknown. */
    private function normalizeFormat(string $format): ?string
    {
        $format = strtolower(trim($format));
        if ($format === 'jpeg') $format = 'jpg';
        return isset(self::FORMATS[$format]) ? $format : null;
    }

    private function formatFromMime(string $mime): ?string
    {
        return ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp'][$mime] ?? null;
    }
}
