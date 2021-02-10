<?php

namespace models;

use Imagick;

class File
{
    public function __construct(
        private string $path,
        private array $files,
        private array $imgRules
    ) {}

    public function checkPath(): bool
    {
        return is_dir($this->path) ?? mkdir($this->path, 0755);
    }

    public function processImage(): bool
    {
        $files = $this->getFiles();

        foreach ($files as $key => $img)
        {
            if ( $img['error'] !== UPLOAD_ERR_OK || $img['size'] > $this->imgRules['max_file_size'] )
                return false;

            if ($this->imgRules['types'][strtolower(pathinfo($img['name'])['extension'])] !== mime_content_type($img['tmp_name']))
                return false;

            list($width, $height, $type, $attr) = getimagesize($img['tmp_name']);

            if ($width > $this->imgRules['max_w'] || $height > $this->imgRules['max_h'] ||
                $width < $this->imgRules['min_w'] || $height < $this->imgRules['min_h'])
                return false;

            $name = array_key_first($files) === $key ? 'main.jpg' : "img_$key.jpg";

            $imagick = new Imagick();
            $imagick->newImage($this->imgRules['min_w'], $height < $this->imgRules['min_h'], 'white');
            $imagick->compositeImage($img['tmp_name'], Imagick::COMPOSITE_OVER, 0, 0);
            $imagick->setImageFormat('jpeg');
            $imagick->setCompressionQuality(97);
            $imagick->setImageFilename($name);

            if (!$imagick->writeImage("$this->path/$name"))
                return false;

            $imagick->destroy();
        }

        return true;
    }

    private function getFiles(): array
    {
        $normalized_array = [];

        foreach ($this->files as $index => $file )
        {
            if (!is_array($file['name']))
            {
                $normalized_array[$index][] = $file;
                continue;
            }

            foreach ( $file['name'] as $idx => $name )
            {
                $normalized_array[$index][$idx] =
                [
                    'name'     => $name,
                    'type'     => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error'    => $file['error'][$idx],
                    'size'     => $file['size'][$idx]
                ];
            }
        }

        return $normalized_array;
    }
}
