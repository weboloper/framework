<?php

namespace Components\Validation;

use Phalcon\Version;
use Components\Model\Users;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\StringLength;



class RegistrationValidator extends Validation
{
    public function initialize()
    {
        $this->add('email', new PresenceOf([
            'message' => 'Email is required',
        ]));

        $this->add('email', new Email([
            'message' => 'Email is not valid',
        ]));

        $this->add('email', new Uniqueness([
            'model' => (int) Version::getId() <= 2001341 ? Users::class : new Users,
            'message' => 'Email already exist',
        ]));

        $this->add('username', new PresenceOf([
            'message' => 'Username is required',
        ]));

        $this->add('username', new Uniqueness([
            'model' => (int) Version::getId() <= 2001341 ? Users::class : new Users,
            'message' => 'Username already exist',
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
