<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Posts;

class PostMeta extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'post_meta';
    }

    public function initialize()
    {
        $this->belongsTo('meta_id', Posts::class, 'id', ['alias' => 'post', 'reusable' => true]);
    }

}