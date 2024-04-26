<?php

namespace App\Services;

use GdImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PictureConverterInterface
{
    public static function convert(UploadedFile $file, int $width): GdImage;
}
