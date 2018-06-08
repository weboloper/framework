<?php

namespace Components\Validation;

 
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
 

class ForgetpassValidator extends Validation
{
    public function initialize()
    {
        $this->add('email', new PresenceOf([
            'message' => 'Email is required',
        ]));

        $this->add('email', new Email([
            'message' => 'Email is not valid',
        ]));

     
    }
}
