<?php

namespace IPValidator;

class ReadCSV
{

    private $csvData;
    private $csvFile;

    function __construct($file)
    {

        $this->csvFile = fopen($file, "r");
        while (!feof($this->csvFile)) {
            $csvtemp = fgetcsv($this->csvFile, 1000);
            if($csvtemp){
                $this->csvData[] = array("date" => $csvtemp[0], "uid" => $csvtemp[1], "displayName" => $csvtemp[2], "course" => $csvtemp[3]);
            }
        }
    }

    public function getCSV()
    {
        return $this->csvData;
    }
}