<?php

namespace App\Controller;

use App\Service\ExcelParser;
use App\Service\WordParser;
use http\Exception\RuntimeException;

class file
{
    /**
     * @var \App\Database $db
     */
    private $db;
    private $twig;

    function __construct($db, $twig)
    {
        $this->db = $db;
        $this->twig = $twig;
    }

    public function main()
    {
        echo 'file main works';
    }

    public function get()
    {
        if (!array_key_exists('id', $_POST)) {
            throw new \RuntimeException('ID missed');
        }

        $id = $_POST['id'];
        $file = $this->db->getFileById($id);
        $name = $file['name'];
        $info = pathinfo($name);
        $ext = $info['extension'];
        if ($ext == 'doc' || $ext == 'docx') {
            $content = WordParser::getContent($ext, $file['path']);
        } else {
            $content = ExcelParser::getContent($ext, $file['path']);
        }

        return json_encode([
            'data' => $content
        ]);
    }

}
