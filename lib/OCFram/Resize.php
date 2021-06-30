<?php

namespace OCFram;

trait Resize
{
    /**
     * Resize the $source image. The result will be $uploadfile
     * The php gd extension must be enabled
     * @param string $source : path to the source image
     * @param mixed $uploadfile : path where the resized image will be placed
     * @param int $width : width of the resized image
     * @param int $height : height of the resized image
     *
     * @return void
     */
    private function resize(string $source, $uploadfile, int $width, int $height): void
    {
        $source = imagecreatefromjpeg($source);
        $destination = imagecreatetruecolor($width, $height);

        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);

        imagecopyresampled($destination, $source, 0, 0, 0, 0, $width, $height, $largeur_source, $hauteur_source);

        imagejpeg($destination, $uploadfile);
    }
}
