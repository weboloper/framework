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
use Components\Model\Posts;

class PostsForm extends Form
{
    public function initialize($entity = null)
    {
        // In edit page the id is hidden
        if (!is_null($entity)) {
            $this->add(new Hidden('id'));
         }
        $title = new Text('title',
            array(
            'placeholder' => "Title",
            'class'       => 'form-control',
            'required'    => true
            )
        );
 
        $this->add($title);

        $slug = new Text('slug',
            array(
            'placeholder' => "Slug",
            'class'       => 'form-control',
            'required'    => true 
            )
        );
 
        $this->add($slug);

        $body = new Textarea('body',
            [
                'data-provide'=> 'markdown',
                'data-iconlibrary' => 'fa',
                 'rows'  =>15
            ]
        );
 
        $this->add($body);

        $excerpt = new Textarea('excerpt',
            [
                'rows'  => 4
            ]
        );
        $this->add($excerpt);

        $status = new Select('status',
                Posts::POST_STATUS
            ,
            [
                'useEmpty' => false,
                'emptyText' => 'auto draft',
                'class' => 'form-control' 
            ]
        );
        $this->add($status);

        $parent =   new Select(
                "parent",
                Posts::find([
                    'type = "page" AND status = :status:',
                    'bind' => [
                        'status' => Posts::STATUS_PUBLISH 
                        ]
                    ]),
                [
                    "using" => [
                        "id",
                        "title",
                    ],
                    'useEmpty' => true,
                    'emptyText' => 'no parent',
                ]
            )
        ;
        $this->add($parent);



 
        $this->add(new Hidden('object'));
        $this->add(new Hidden('type'));
        $csrf = new Hidden('csrf');
        $this->add($csrf);
 
        
       
    }
}