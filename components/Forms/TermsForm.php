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
 
class TermsForm extends Form
{
    public function initialize($entity = null)
    {
        // In edit page the id is hidden
        if (!is_null($entity)) {
            $this->add(new Hidden('term_id'));
         }
        $name = new Text('name',
            array(
            'placeholder' => "Name",
            'class'       => 'form-control',
            'required'    => true
            )
        );
 
        $this->add($name);

        $slug = new Text('slug',
            array(
            'placeholder' => "Slug",
            'class'       => 'form-control',
            'required'    => true 
            )
        );
 
        $this->add($slug);

        $description = new Textarea('description',
            [
                'data-provide'=> 'markdown',
                'data-iconlibrary' => 'fa',
                 'rows'  =>15
            ]
        );
 
        $this->add($description);

 
        $this->add(new Hidden('object'));
        $this->add(new Hidden('taxonomy'));
        $csrf = new Hidden('csrf');
        $this->add($csrf);
 
        
       
    }
}