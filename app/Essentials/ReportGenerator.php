<?php 

namespace App\Essentials;

class ReportGenerator{
    
    public $fileName;
    public $tableHead = [];
    public $tableItems = [];

    function __construct($fileName = 'export', $properties = [])
    {   
        $this->fileName = $fileName;
        if( isset($properties['tableHead']) )
            $this->tableHead = $properties['tableHead'];
    }

    //File Name
    public function fileName($fileName = NULL){
        if( $fileName !=  NULL )
            $this->fileName = $fileName;
        return $this->fileName;
    }

    //Table Head
    public function setTableHead($tableHead = []){
        $this->tableHead = $tableHead;
    }

    //Table Head
    public function setTableHeadCell($cell = []){
        $this->tableHead[] = $cell;
    }

    //Table Items
    public function addRow($row=[]){
        $this->tableItems[] = $row;
    }

    public function html(){
        print_r($this);
    }
}