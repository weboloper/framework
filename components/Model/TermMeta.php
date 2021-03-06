<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\Terms;

class TermMeta extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'term_meta';
    }

    public function setTermId($term_id) {
        $this->term_id = $term_id;
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
        $this->hasOne('term_id', Terms::class, 'id', ['alias' => 'term', 'reusable' => true]);
    }


}