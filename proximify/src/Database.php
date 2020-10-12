<?php

namespace App;

use App\Controller\file;

/**
 * Class Database
 * @package App
 * use PDO database access abstraction layer to access mysql
 */
class Database
{
    /**
     * @return array
     */
    public function getFileList()
    {
        $fileArr = [];
        $directory = dirname(__DIR__, 1) . "/uploads";
        $files = array_diff(scandir($directory), array('..', '.', '.gitkeep'));
        foreach ($files as $key => $value){
            $fileArr[sizeof($fileArr)] = [
                                        'id' => $key,
                                        'name' => $value,
                                        'path' => './uploads/' . $value
            ];
        }
        return $fileArr;
    }

    /**
     * @param $id
     * @return array
     */
    public function getFileById($id)
    {
        $directory = dirname(__DIR__, 1) . "/uploads";
        $files = array_diff(scandir($directory), array('..', '.', '.gitkeep'));
        return [
            'id' => $id,
            'name' => $files[$id],
            'path' => './uploads/' . $files[$id]
        ];
    }
}
