<?php

namespace wish\Validation;

use Respect\Validation\Validator as Respect;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors;

    public function validate(Request $request, array $rules){


        foreach ($rules as $field => $rule){
            try {
                $input = filter_var($request->getParam($field), FILTER_SANITIZE_STRING) ;
                $rule->setName(ucfirst($field))->assert($input);

            }catch (NestedValidationException $e){
                $this->errors[$field] = $e->getMessages();
            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public  function failed()
    {
        return !empty($this->errors);
    }
}