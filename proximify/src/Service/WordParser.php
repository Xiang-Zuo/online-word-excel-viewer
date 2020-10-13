<?php

namespace App\Service;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * Class WordParser
 * @package App\Service
 */
class WordParser implements DocInterface
{
    /**
     * @param $ext
     * @param $path
     * @return mixed
     * @throws Exception
     */
    public static function getContent($ext, $path)
    {
        $config = [
            'doc' => 'MsDoc',
            'docx' => 'Word2007'
        ];
        // MsDoc => Doc; Word2007 => Docx
        $phpWordReader = \PhpOffice\PhpWord\IOFactory::createReader($config[$ext]);
        // read source
        if (!$phpWordReader->canRead($path)) {
            throw new \RuntimeException('Can\'t read file');
        }

        $phpWord = $phpWordReader->load($path);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        // it only have save method, read the source code and find getContent() method
        return $objWriter->getContent();
    }

    /**
     * @param $content
     * @param $path
     * @param $title
     * @return bool
     * @throws Exception
     */
    public static function addContent($content, $path, $title)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText($title, ['name' => 'Calibri', 'size' => 20, 'align' => 'center']);
        $section->addText($content, ['name' => 'Calibri', 'size' => 15, 'align' => 'both']);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($path);
        return true;
    }
}
