<?php

namespace App;

class ItemValidator 
{
    public static function validate($item)
    {
        $errors = [];
        if(count($item) !== 6) {
            return "Invalid fields number";
        }
        foreach(array_keys($item) as $key) {
            if (mb_detect_encoding($item[$key]) !== "ASCII") {
                $errors[] = "Invalid encoding for field \"{$key}\"";
            }
        }
        if(!is_numeric($item['stock']) || (int) $item['stock'] < 0) {
            $errors[] = "Invalid number for field \"stock\"";
        }

        if(!is_numeric($item['price']) || (float) $item['price'] < 0) {
            $errors[] = "Invalid number for field \"price\"";
        }
        if(!empty($errors)) {
            return implode(";\n", $errors);
        }
        if((int) $item['stock'] < 10 && (float) $item['price'] < 5) {
            return "Stock is lower than 10 and price is lower than 5";
        }
        return false;
    }
}