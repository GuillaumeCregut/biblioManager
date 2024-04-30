<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use GdImage;

class PictureConvert implements PictureConverterInterface
{
    public static function convert(UploadedFile $file, int $width): GdImage
    {
        $typeFile = $file->getMimeType();
        switch ($typeFile) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file->getPathname());
                break;
            case 'image/png':
                $image = imagecreatefrompng($file->getPathname());
                break;
            default:
                throw new \InvalidArgumentException('Unsupported image mmime type');
        }
        if (!$image) {
            throw new \InvalidArgumentException('Unsupported image type');
        }
        if (imagesx($image) < $width) {
            return $image;
        }
        $result =  imagescale($image, $width);
        if (!$result) {
            throw new \InvalidArgumentException('Unable to resize picture');
        }
        return $result;
    }
}
