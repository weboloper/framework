<?php

namespace Components\Validation;

use Phalcon\Version;
use Components\Model\Users;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Confirmation;

class PostsValidator extends Validation
{
    public function initialize()
    {
        $this->add('title', new PresenceOf([
            'message' => 'Title is required',
        ]));

        // $this->add('body', new PresenceOf([
        //     'message' => 'Body is required',
        // ]));
        
        $this->add('status', new PresenceOf([
            'message' => 'Status is required',
        ]));

        $this->setFilters("title", ["string" , "trim"]);

       
    }
}
