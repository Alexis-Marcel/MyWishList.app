<?php

namespace wish\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use wish\Models\User;

class PasswordConfirmation extends AbstractRule
{
    protected $password;

    public function __construct($pass)
    {
        $this->password = $pass;
    }

    public function validate($input): bool
    {
        return $input === $this->password;
    }
}