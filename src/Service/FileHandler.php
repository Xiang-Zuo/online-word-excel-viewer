<?php

namespace App\Service;

/**
 * Class FileHandler
 * @package App\Service
 */
class FileHandler
{
    /**
     * @param $file
     * @param $path
     * @return mixed
     */
    public static function move($file, $path)
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

            // Check $_FILES['uploadFile']['error'] value.
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
     * @param string $rootPath
     * @return string
     */
    public static function generatePath($fileName, $ext, $rootPath = '')
    {
        if (empty($rootPath)) {
            $rootPath = dirname(__DIR__, 2);
        }
        $directory = $rootPath . "/www/uploads";
        $fileList = FileHandler::filterByExtension(scandir($directory));
        $count = FileHandler::getExistFileCount($fileList, $fileName);
        return sprintf(
            $rootPath . '/www/uploads/%s',
            ($count > 0) ? $fileName . '(' . $count . ').' . $ext : $fileName . '.' . $ext
        );
    }

    /**
     * @param $fileList
     * @return array
     */
    public static function filterByExtension($fileList)
    {
        $extList = ['doc', 'docx', 'csv', 'xlsx', 'xls'];
        $result = [];

        foreach ($fileList as $value) {
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            if (in_array($ext, $extList)) {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $fileList
     * @param $fileName
     * @return int
     */
    public static function getExistFileCount($fileList, $fileName)
    {
        $count = 0;
        $pattern = '/^' . $fileName . '(\(\d+\))?$/i';

        foreach ($fileList as $value) {
            if (preg_match($pattern, pathinfo($value, PATHINFO_FILENAME))) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param $path
     * @return array
     */
    public static function getFileList($path)
    {
        if (empty($path)) {
            $path = dirname(__DIR__, 2);
        }
        $directory = $path . "/www/uploads";
        $fileList = FileHandler::filterByExtension(scandir($directory));
        $fileArr = [];
        foreach ($fileList as $key => $value) {
            $fileArr[sizeof($fileArr)] = [
                'id' => $key,
                'name' => $value,
                'path' => './uploads/' . $value
            ];
        }
        return $fileArr;
    }
}
