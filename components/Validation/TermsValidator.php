<?php

namespace Components\Validation;

use Phalcon\Version;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex  as RegexValidator;
use Phalcon\Validation\Validator\Email;

class TermsValidator extends Validation
{
    public function initialize()
    {
        $this->add('name', new PresenceOf([
            'message' => 'Name is required',
        ]));

        $this->add('slug', new PresenceOf([
            'message' => 'Slug is required',
        ]));

        $this->add('slug', new RegexValidator([
            "pattern" => "/[a-zA-Z0-9\-]+/",
            'message' => 'Slug is not valid',
        ]));

        $this->add('taxonomy', new PresenceOf([
            'message' => 'Taxonomy is required',
        ]));
        
         
        $this->setFilters("name", ["string" , "trim"]);
        $this->setFilters("taxonomy", ["string" , "trim"]);
 
       
    }

}
