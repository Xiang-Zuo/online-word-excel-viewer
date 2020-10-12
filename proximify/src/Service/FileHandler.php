<?php

namespace App\Service;

class FileHandler
{
    /**
     * @param $file
     * @return string
     */
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

            // check filesize
            if ($file['size'] > 10000000) {
                throw new \RuntimeException('Exceeded filesize limit.');
            }

            $info = pathinfo($file['name']);
            $ext = $info['extension'];

            // create unique filename
            $path = FileHandler::generatePath($info['filename'], $ext);

            // move the file to upload folder, store it like a tmp file
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

    /**
     * go to the uploads directory to check if any file in /uploads has the same name as the new file
     * if duplicate add ($count) after file name
     * @param $fileName
     * @param $ext
     * @return string
     */
    public static function generatePath($fileName, $ext)
    {
        $directory = dirname(__DIR__, 2) . "/uploads";
        $files = array_diff(scandir($directory), array('..', '.', '.gitkeep'));
        $count = 0;
        foreach ($files as $key => $value){
            if (substr($value, 0,strlen($fileName)) === $fileName){
                $count++;
            }
        }
        return sprintf('./uploads/%s',
            ($count > 0) ? $fileName . ' (' . $count . ').'. $ext : $fileName . '.' . $ext
        );
    }
}
