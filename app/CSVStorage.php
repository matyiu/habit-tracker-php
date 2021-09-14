<?php

namespace App;

class CSVStorage
{
    private $storagePath;

    public function __construct()
    {
        require './vars/config.php';

        $this->storagePath = $config['ROOT_DIR'] . '/storage/';
    }

    /**
     * Returns an associative array with values from the CSV file
     * 
     * @param string $path
     * 
     * @return array
     */
    public function read(string $path)
    {
        $absPath = $this->storagePath . $path;
        if (!file_exists($absPath)) {
            return false;
        }

        
        $file = fopen($absPath, 'r');
        $header = null;
        $data = [];
        while (($line = fgetcsv($file)) !== false) {
            if (!$header) {
                $header = $line;
            } else {
                array_push($data, $this->convertArrToAssocArr($line, $header));
            }
        }
        

        return $data;
    }

    private function convertArrToAssocArr(array $array, array $fields)
    {
        $assocArr = [];
        foreach ($array as $key => $value) {
            $assocArr[$fields[$key]] = $value;
        }

        return $assocArr;
    }
}