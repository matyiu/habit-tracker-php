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

    public function write(string $path, array $data)
    {
        if (empty($data)) {
            return false;
        }

        $absPath = $this->storagePath . $path;
        file_put_contents($absPath, $this->convertAssocArrToCsv($data));

        return $data;
    }

    private function convertAssocArrToCsv(array $data)
    {
        $headers = array_keys($data[0]);
        $csvData = implode(',', $headers) . PHP_EOL;
        foreach ($data as $value) {
            $csvData .= implode(',', $value) . PHP_EOL;
        }

        return $csvData;
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