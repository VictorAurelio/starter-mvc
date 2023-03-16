<?php

namespace App\Core\Validation\Rule;

use App\Core\Validation\Rule\Rule;
class RequiredRule implements Rule
{
    public function validate(array $data, string $field, array $params)
    {
        return !empty($data[$field]);
    }

    public function getMessage(array $data, string $field, array $params)
    {
        return "{$field} is required";
    }
}
