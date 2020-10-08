<?php

namespace App\Service;

class FileHandler
{
    public static function move($file)
    {
        try {
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($file['error']) ||
                is_array($file['error'])
            ) {
                throw new \RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($file['size'] > 10000000) {
                throw new \RuntimeException('Exceeded filesize limit.');
            }

            $info = pathinfo($file['name']);
            $ext = $info['extension'];

            $path = FileHandler::generatePath($ext);

            if (!move_uploaded_file(
                $file['tmp_name'],
                $path
            )) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }
        } catch (\RuntimeException $e) {

            echo $e->getMessage();

        }

        return $path;
    }

    public static function generatePath($ext)
    {
        return sprintf('./uploads/%s.%s',
            md5(uniqid()),
            $ext
        );
    }
}
