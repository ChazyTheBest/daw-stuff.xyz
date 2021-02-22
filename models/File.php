<?php

namespace models;

use Exception;
use framework\App;
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
        return !is_dir($this->path) ? mkdir($this->path, 0755, true) : true;
    }

    public function processImage(): bool
    {
        $files = $this->getFiles();

        foreach ($files as $key => $img)
        {
            if ($img['error'] !== UPLOAD_ERR_OK)
                throw new Exception(App::t('error', 'file_upload'));

            if ($img['size'] > $this->imgRules['max_file_size'])
                throw new Exception(App::t('error', 'max_file_size', $this->imgRules['max_file_size']));

            if ($this->imgRules['types'][strtolower(pathinfo($img['name'])['extension'])] !== mime_content_type($img['tmp_name']))
                throw new Exception(App::t('error', 'mime_type', [ implode(',', $this->imgRules['types']) ]));

            list($width, $height, $type, $attr) = getimagesize($img['tmp_name']);

            if ($width > $this->imgRules['resolution']['max_w'] || $height > $this->imgRules['resolution']['max_h'] ||
                $width < $this->imgRules['resolution']['min_w'] || $height < $this->imgRules['resolution']['min_h'])
                throw new Exception(App::t('error', 'img_resolution', [ "$this->imgRules[resolution][max_w] by $this->imgRules[resolution][max_h]" ]));

            $name = array_key_first($files) === $key ? 'main.jpg' : "img_$key.jpg";

            $imagick = new Imagick();
            $imagick->newImage($this->imgRules['resolution']['min_w'], $height < $this->imgRules['resolution']['min_h'], 'white');
            $imagick->compositeImage(new Imagick($img['tmp_name']), Imagick::COMPOSITE_OVER, 0, 0);
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

        foreach ( $this->files as $key => $val )
        {
            foreach ( $val as $idx => $name )
            {
                $normalized_array[$idx][$key] = $name;
            }
        }

        return $normalized_array;
    }
}
