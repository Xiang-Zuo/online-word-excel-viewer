<?php

namespace App\Controller;

use App\Service\ExcelParser;
use App\Service\WordParser;
use \RuntimeException;

/**
 * Class file
 * @package App\Controller
 */
class file
{
    private $twig;
    private string $rootDir;

    /**
     * file constructor.
     * @param $twig
     * @param $rootDir
     */
    public function __construct($twig, string $rootDir)
    {
        $this->twig = $twig;
        $this->rootDir = $rootDir;
    }

    /**
     * @return string|null
     */
    public function run(): ?string
    {
        if (!empty($_POST['name'])) {
            throw new RuntimeException('ID missed');
        }
        $name = $_POST['name'];
        $path = $this->rootDir . '/www/uploads/' . $name;
        $info = pathinfo($name);
        $ext = $info['extension'];
        if ($ext == 'doc' || $ext == 'docx') {
            $content = WordParser::getContent($ext, $path);
        } else {
            $content = ExcelParser::getContent($ext, $path);
        }

        return json_encode(
            [
                'data' => $content
            ]
        );
    }
}
