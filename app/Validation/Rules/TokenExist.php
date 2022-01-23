<?php

namespace wish\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use wish\Models\Liste;

class TokenExist extends AbstractRule
{
    protected $password;

    public function validate($input): bool
    {
        return Liste::where('token', $input)->count() === 1;
    }
}