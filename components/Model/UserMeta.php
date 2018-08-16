<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Users;

class UserMeta extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'user_meta';
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
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
        $this->hasOne('user_id', Users::class, 'id', ['alias' => 'user', 'reusable' => true]);
    }
}