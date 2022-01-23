<?php

namespace wish\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class NameAvailableException extends ValidationException
{
    public $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Name is already taken.',
        ],
    ];

}