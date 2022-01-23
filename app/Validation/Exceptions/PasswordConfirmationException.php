<?php

namespace wish\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class PasswordConfirmationException extends ValidationException
{
    public $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Password does not match.',
        ],
    ];

}