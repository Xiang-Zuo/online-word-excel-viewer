<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelParser implements DocInterface
{
    public static function getContent($ext, $path)
    {
        $config = [
            'xls' => 'Xls',
            'xlsx' => 'Xlsx',
            'csv' => 'Csv'
        ];
        // MsDoc => Doc; Word2007 => Docx
        $phpExcelReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($config[$ext]);
        // read source
        if (!$phpExcelReader->canRead($path)) {
            throw new \RuntimeException('Can\'t read file');
        }
        $phpExcel = $phpExcelReader->load($path);
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($phpExcel, 'Html');
        return $objWriter->generateHTMLAll();
    }

    public static function addContent($content, $path, $title = '')
    {
        $phpExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $phpExcel->getActiveSheet();
        $rows = preg_split('/\r\n|\r|\n/', $content);
        foreach ($rows as $index => $row) {
            $items = explode('|', $row);
            $num = 65;
            foreach ($items as $item) {
                $cellNum = chr($num) . ($index + 1);
                $sheet->setCellValue($cellNum, $item);
                $num++;
            }
        }

        $writer = new Xlsx($phpExcel);
        $writer->save($path);

        return true;
    }
}
