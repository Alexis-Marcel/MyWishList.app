<?php

namespace wish\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class TokenExistException extends ValidationException
{
    public $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => "This token doesn't exist",
        ],
    ];

}