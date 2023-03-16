<?php

namespace App\Core\Validation\Rule\Data;

use App\Core\Exceptions\AppInvalidArgumentException;
use App\Core\Validation\Rule\Data\DataSanitizer;

class DataValidator
{
    public function __construct(array $dirtyData)
    {
        if (empty($dirtyData)) {
            throw new AppInvalidArgumentException('No data was submitted.');
        }
        if (is_array($dirtyData)) {
            foreach ($this->cleanData($dirtyData) as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    private function cleanData(array $dirtyData) : array
    {
        $cleanData = DataSanitizer::clean($dirtyData);
        if($cleanData) {
            return $cleanData;
        }
    }
}
