<?php

namespace App\Core\Validation\Rule;

use App\Core\Validation\Rule\Rule;
use InvalidArgumentException;

class MatchRule implements Rule
{
    public function validate(array $data, string $field, array $params)
    {
        if (empty($data[$field])) {
            return true;
        }

        if (empty($params[0])) {
            throw new InvalidArgumentException('specify a field to compare');
        }

        $compareField = $params[0];

        return $data[$field] === $data[$compareField];
    }

    public function getMessage(array $data, string $field, array $params)
    {
        $compareField = $params[0];

        return "{$field} and {$compareField} do not match";
    }
}
