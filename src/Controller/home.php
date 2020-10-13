<?php

namespace App\Controller;

use App\Service\FileHandler;
use \RuntimeException;

/**
 * Class home
 * @package App\Controller
 */
class home
{
    private $twig;
    private string $rootDir;

    /**
     * home constructor.
     * @param $twig
     * @param string $rootDir
     */
    public function __construct($twig, string $rootDir)
    {
        $this->twig = $twig;
        $this->rootDir = $rootDir;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        if (isset($_POST["submit"])) {
            $name = $_FILES['fileToUpload']['name'];
            $info = pathinfo($name);
            $ext = $info['extension'];
            $path = FileHandler::generatePath($info['filename'], $ext, $this->rootDir);
            FileHandler::move($_FILES['fileToUpload'], $path);
        }
        if (isset($_POST["submit-content"])) {
            if (!array_key_exists('type', $_POST)) {
                throw new RuntimeException('Type Value is null');
            }
            if (empty($_POST['name'])) {
                echo "Error: file name is required";
                throw new RuntimeException("File name is empty");
            }
            if ($_POST['type'] == 'word') {
                $ext = 'docx';
                $service = 'App\Service\WordParser';
            } else {
                $ext = 'xlsx';
                $service = 'App\Service\ExcelParser';
            }
            $title = isset($_POST['title']) ? $_POST['title'] : '';
            $content = $_POST['content'];
            $path = FileHandler::generatePath($_POST['name'], $ext, $this->rootDir);
            $service::addContent($content, $path, $title);
        }
        $fileArr = FileHandler::getFileList($this->rootDir);
        return $this->twig->render('index.html.twig', ['files' => $fileArr]);
    }
}
