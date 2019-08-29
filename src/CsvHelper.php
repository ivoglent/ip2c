<?php


namespace ivoglent\ip2c;


class CsvHelper
{
    /**
     * @param $file
     * @param $str
     * @param $columnIndex
     * @return array|bool|false|null
     */
    public static function findInCsv($file, $str, $columnIndex) {
        $f = fopen($file,"r");
        while (!feof($f)) {
            $data = fgetcsv($f);
            if (isset($data[$columnIndex]) && $data[$columnIndex] === $str) {
                fclose($f);
                return $data;
            }
        }
        fclose($f);
        return false;
    }

    /**
     * @param $file
     * @param null $lineCallback
     * @return array
     */
    public static function loadCsv($file, $lineCallback = null) {
        $data = [];
        if (file_exists($file)) {
            $f = fopen($file,'r');
            while (!feof($f)) {
                $line = fgetcsv($f);
                if ($lineCallback) {
                    if (call_user_func($lineCallback, $line)) {
                        $data = null;
                        fclose($f);
                        return null;
                    }
                } else {
                    $data[] = $line;
                }
            }
            fclose($f);
        }
        return $data;
    }
}