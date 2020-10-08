<?php

namespace App\Controller;
use App\Service\FileHandler;
use http\Exception\RuntimeException;

class home
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

    public function main() {
        if (isset($_POST["submit"])) {
            $name = $_FILES['fileToUpload']['name'];
            $path = FileHandler::move($_FILES['fileToUpload']);
            $this->db->addFile($name, $path);
        }
        if (isset($_POST["submit-content"])) {
            // TODO: EXCP
            if (!array_key_exists('type', $_POST)){
                throw new RuntimeException('Type Value is null');
            }
            if (empty($_POST['name'])){
                throw new RuntimeException("File name cannot be empty");
            }
            if ($_POST['type'] == 'word') {
                $ext = 'docx';
                $service = 'App\Service\WordParser';
            } else {
                $ext = 'xlsx';
                $service = 'App\Service\ExcelParser';
            }
            $title = isset($_POST['title']) ? $_POST['title']: '';
            $name = $_POST['name'] . '.' . $ext;
            $content = $_POST['content'];
            $path = FileHandler::generatePath($ext);
            $service::addContent($content, $path, $title);
            $this->db->addFile($name, $path);
        }
        $fileArr = $this->db->getFileList();
        // display web page
        return $this->twig->render('index.html.twig', ['files' => $fileArr]);
    }

}
