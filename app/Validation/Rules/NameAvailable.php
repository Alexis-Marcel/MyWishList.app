<?php

namespace wish\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use wish\Models\User;

class NameAvailable extends AbstractRule
{
    public function validate($input): bool
    {
        return User::where('name', $input)->count() === 0;
    }
}