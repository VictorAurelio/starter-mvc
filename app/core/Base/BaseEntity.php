<?php

namespace App\Core\Base;

use App\Core\Base\Exceptions\BaseInvalidArgumentException;
use App\Core\Helpers\Sanitizer;

class BaseEntity
{
    public function __construct(array $dirtyData)
    {
        if (empty($dirtyData)) {
            throw new BaseInvalidArgumentException('No data was submitted.');
        }
        if (is_array($dirtyData)) {
            foreach ($this->cleanData($dirtyData) as $key => $value) {
                $this->$key = $value;
            }
        }
    }
    private function cleanData(array $dirtyData) : array
    {
        $cleanData = Sanitizer::clean($dirtyData);
        if($cleanData) {
            return $cleanData;
        }
    }
}
