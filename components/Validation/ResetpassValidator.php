<?php

namespace Components\Validation;

use Phalcon\Version;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Confirmation;

class ResetpassValidator extends Validation
{
    public function initialize()
    {

        $this->add('password', new PresenceOf([
            'message' => 'Password is required',
        ]));

        $this->add('password', new Confirmation([
            'with' => 'repassword',
            'message' => 'Password and Repeat Password must match',
        ]));

        $this->add('repassword', new PresenceOf([
            'message' => 'Repeat Password is required',
        ]));
    }
}
