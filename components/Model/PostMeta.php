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

    public function setPostId($post_id) {
        $this->post_id = $post_id;
        return $this;
    }

    public function setMetaKey($meta_key) {
        $this->meta_key = $meta_key;
        return $this;
    }

    public function setMetaValue($meta_value) {
        $this->meta_value = $meta_value;
        return $this;
    }


    public function initialize()
    {
        $this->hasOne('meta_id', Posts::class, 'id', ['alias' => 'post', 'reusable' => true]);
    }

}