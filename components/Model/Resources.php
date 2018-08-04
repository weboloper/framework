<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

class Resources extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'resources';
    }

     /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

 

    public function initialize()
    {
        // $this->belongsTo('role_id', Roles::class, 'id', ['alias' => 'role', 'reusable' => true]);
    }

    


}