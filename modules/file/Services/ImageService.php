<?php

namespace Modules\File\Services;

use Ramsey\Uuid\Uuid;

class ImageService
{
    public function upload($image)
    {
        if ($image->isValid() && !$image->hasMoved()) {
            $path = ROOTPATH . 'public/image/news/';
            $name = $image->getRandomName();
            $image->move($path, $name);
            $webpImage = $this->convertToWebp($path, $name);
            $this->delete($path . $name);
            return $webpImage;
        }
    }

    public function delete($filePath)
    {
        return unlink($filePath);
    }

    private function convertToWebp($path, $fileName)
    {
        $name = Uuid::uuid4() . '.webp';
        $filePath = 'image/news/';
        \Config\Services::image()
            ->withFile($path . $fileName)
            ->convert(IMAGETYPE_WEBP)
            ->save($path . $name);
        return $filePath . $name;
    }
}