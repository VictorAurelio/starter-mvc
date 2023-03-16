<?php

namespace App\Core\Validation\Rule\Data;

use App\Core\Exceptions\AppInvalidArgumentException;

class DataSanitizer
{
    public static function clean(array $dirtyData): array
    {
        $input = [];
        if(count($dirtyData) > 0) {
            foreach($dirtyData as $key => $value) {
                if(!isset($key)) {
                    throw new AppInvalidArgumentException('Invalid Key');
                }
                if(!is_array($value)) {
                    $value = trim(stripslashes($value));
                }
                switch ($value) {
                    case is_int($value):
                        $input[$key] = isset($value) ? filter_var($value, FILTER_SANITIZE_NUMBER_INT) : '';
                        break;
                    case is_string($value):
                        $input[$key] = isset($value) ? filter_var($value, FILTER_UNSAFE_RAW) : '';
                        break;
                    case is_array($value):
                        if(count($value) > 0) {
                            foreach($value as $arrKey => $arrValue) {
                                if(is_int($arrValue)) {
                                    $input[$arrKey] = isset($arrValue) ? filter_var($arrValue, FILTER_SANITIZE_NUMBER_INT) : '';
                                }else {
                                    $input[$arrKey] = isset($arrValue) ? filter_var($value, FILTER_UNSAFE_RAW) : '';
                                }
                            }
                        }
                    break;                
                }
            }
            if(isset($input) && $input != '') {
                return (array)$input;
            }
        }
    }
}