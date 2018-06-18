<?php
namespace Components\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Radio;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Textarea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;
use Components\Model\Users;

class UsersForm extends Form
{
    public function initialize($entity = null)
    {
        // In edit page the id is hidden
        if (!is_null($entity)) {
            $this->add(new Hidden('id'));
         }
        $name = new Text('name',
            array(
            'placeholder' => "Name",
            'class'       => 'form-control',
            'required'    => true
            )
        );
 
        $this->add($name);

        $email = new Text('email',
            array(
            'placeholder' => "Email",
            'class'       => 'form-control',
            'required'    => true 
            )
        );
 
        $this->add($email);

      

        $status = new Select('status',
                Users::USER_STATUS
            ,
            [
                'useEmpty' => false,
                'class' => 'form-control' 
            ]
        );
        $this->add($status);


 
        $this->add(new Hidden('object'));
        $csrf = new Hidden('csrf');
        $this->add($csrf);
 
        
       
    }
}