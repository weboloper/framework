<?php

namespace Components\Validation;

use Phalcon\Version;
use Components\Model\Users;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class UsersEditValidator extends Validation
{
    public function initialize()
    {
        $this->add('email', new PresenceOf([
            'message' => 'Email is required',
        ]));

        $this->add('email', new Email([
            'message' => 'Email is not valid',
        ]));

  

        $this->add('username', new PresenceOf([
            'message' => 'Username is required',
        ]));

  
        if(!$this->config->app->auth->usernames){
            $this->add('username', new RegexValidator([
                "pattern" => "/^([a-z0-9_@.]*)$/i",
                'message' => 'Username only accepts alphanumeric, no spaces',
            ]));
        }else {
            $this->add('username', new RegexValidator([
                "pattern" => "/^([a-z0-9_]*)$/i",
                'message' => 'Username only accepts alphanumeric, no spaces',
            ]));

            $this->add('username', new StringLength([
                "max"            => 20,
                "min"            => 3,
                'message' => 'Username length min:3 max:20 characters',
            ]));
        }
 
        $this->add('name', new RegexValidator([
            "pattern" => "/^([a-zA-ZÖÇŞİĞÜöçşığü\ ]*)$/i",
            'message' => 'Name must be alphabetic characters only',
            "allowEmpty" => true
        ]));

        
    }
}
