<?php

namespace App\Service;


interface DocInterface
{
    public static function getContent($ext, $path);

    public static function addContent($content, $path, $title);

}
