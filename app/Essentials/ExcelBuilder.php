<?php

/**
 * Excel Wrapper by Jobin Das!
 */

namespace App\Essentials;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelBuilder
{
    public $spreadsheet;
    public $fileName;
    public $properties = [];
    public $defaultProperties = [
        'creator' => 'Blue sky Technology Consultants FZE',
        'lastModified' => 'Blue sky Technology Consultants FZE',
        'title' => 'Exported from Real Estate',
        'subject' => 'Exported from Real Estate',
        'description' => 'Exported from Real Estate',
        'keywords' => 'Exported from Real Estate',
        'category' => 'Exports'
    ];
    public $textAlignmentStrings = ['center', 'left', 'right', 'top', 'bottom', 'general', 'centerContinuous', 'justify'];

    //Constructor
    function __construct($fileName = 'file', $properties = [])
    {
        $this->properties = $properties;
        $this->fileName = $fileName;
        $this->spreadsheet = new Spreadsheet();

        $this->setProperties();
    }

    //Set Cell Value
    public function setCell($cell = NULL, $value = NULL, $cellProperties = [])
    {
        $row = $this->spreadsheet->getActiveSheet();

        //Setting Value
        $row->setCellValue($cell, $value);

        //Text Bold
        if (isset($cellProperties['makeBold']) && $cellProperties['makeBold']) {
            $this->setBold($cell);
        }

        //Font Size
        if (isset($cellProperties['fontSize']) && $cellProperties['fontSize'] >= 8) {
            $this->setFontSize($cell, $cellProperties['fontSize']);
        }

        //Text Alignment
        if (isset($cellProperties['textAlignment']) && in_array($cellProperties['textAlignment'], $this->textAlignmentStrings)) {
            $this->setTextAlignment($cell, $cellProperties['textAlignment']);
        }

        //set Auto width
        if (isset($cellProperties['autoWidthIndex']) && $cellProperties['autoWidthIndex'] > 0) {
            $this->setAutoWidth($cellProperties['autoWidthIndex']);
        }

        //Background Color
        if (isset($cellProperties['backgroundColor']) && $cellProperties['backgroundColor'] != NULL) {
            $this->setBackgroundColor($cell, $cellProperties['backgroundColor']);
        }

        return true;
    }

    //Set Multiple Cell Value
    public function setCellMultiple($array_of_data = [])
    {
        foreach ($array_of_data as $each) {
            $this->setCell($each[0], $each[1], isset($each[2]) && is_array($each[2]) ? $each[2] : []);
        }
        return $this->spreadsheet->getActiveSheet();
    }

    //set Background cell
    public function setBackgroundColor($cell = NULL, $color = 'FFFFFF')
    {
        if ($cell != NULL)
            $this->spreadsheet->getActiveSheet()
                ->getStyle($cell)
                ->getFill()
                ->setFillType('solid')
                ->getStartColor()
                ->setARGB($color);
        return;
    }

    //set Background Range
    public function setBackgroundColorRange($from = NULL, $to = NULL, $color = 'FFFFFF')
    {
        if ($from != NULL && $to != NULL)
            $this->spreadsheet->getActiveSheet()
                ->getStyle($from.':'.$to)
                ->getFill()
                ->setFillType('solid')
                ->getStartColor()
                ->setARGB($color);
        return;
    }

    //Merge Cells
    public function mergeCells($from, $to)
    {
        $row = $this->spreadsheet->getActiveSheet();
        $row->mergeCells($from . ':' . $to);
        return true;
    }

    //Merge Cells
    public function mergeCenterCells($from, $to)
    {
        $row = $this->spreadsheet->getActiveSheet();
        $this->mergeCells($from, $to);
        $row->getStyle($from . ':' . $to)->getAlignment()->setHorizontal('center');
        return true;
    }

    //set Column Width auto
    public function setAutoWidth($columnIndex = 0)
    {
        $row = $this->spreadsheet->getActiveSheet();
        if ($columnIndex > 0)
            $row->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
        return true;
    }

    //Set Bold
    public function setBold($cell = NULL)
    {
        $row = $this->spreadsheet->getActiveSheet();
        if ($cell != NULL)
            $row->getStyle($cell)->getFont()->setBold(true);
        return true;
    }

    //set ont Size
    public function setFontSize($cell = NULL, $fontSize = 11)
    {
        $row = $this->spreadsheet->getActiveSheet();
        if ($cell != NULL)
            $row->getStyle($cell)->getFont()->setSize($fontSize);
        return true;
    }

    //Text Alignment
    public function setTextAlignment($cell = NULL, $alignmentString = NULL)
    {
        $row = $this->spreadsheet->getActiveSheet();
        if ($cell != NULL && in_array($alignmentString, $this->textAlignmentStrings))
            $row->getStyle($cell)->getAlignment()->setHorizontal($alignmentString);
        return true;
    }

    //setWorkSheetTitle
    public function setWorkSheetTitle($title = 'default')
    {
        $this->spreadsheet->getActiveSheet()->setTitle($title);
        return true;
    }


    //Initializing Properties
    public function setProperties()
    {
        $this->spreadsheet->getProperties()->setCreator($this->getProperty('creator'))
            ->setLastModifiedBy($this->getProperty('lastModified'))
            ->setTitle($this->getProperty('title'))
            ->setSubject($this->getProperty('subject'))
            ->setDescription($this->getProperty('description'))
            ->setKeywords($this->getProperty('keywords'))
            ->setCategory($this->getProperty('category'));
        return;
    }

    //Selecting Default Properties
    public function getProperty($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
        return $this->defaultProperties[$property];
    }

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    public function setWorkSheet($index = 0)
    {
        $this->spreadsheet->setActiveSheetIndex($index);
    }

    // Redirect output to a client’s web browser (Xlsx)
    public function output()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->fileName . '.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
