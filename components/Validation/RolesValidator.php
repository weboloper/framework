<?php

namespace Components\Validation;

use Phalcon\Version;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex  as RegexValidator;
use Phalcon\Validation\Validator\Email;

class RolesValidator extends Validation
{
    public function initialize()
    {
        $this->add('name', new PresenceOf([
            'message' => 'Name is required',
        ]));

        $this->add('description', new PresenceOf([
            'message' => 'Slug is required',
        ]));

         
        $this->setFilters("name", ["string" , "trim"]);
  
       
    }

}
