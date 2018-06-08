<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\TermRelationships;
use Components\Model\TermMeta;
use Components\Model\Posts;

class Terms extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'terms';
    }

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            TermRelationships::class,
            'term_id',
            'post_id',
            Posts::class,
            'id',
            ['alias' => 'posts']
        );

        $this->hasMany('term_id', TermMeta::class, 'term_id', ['alias' => 'meta', 'reusable' => true]);
    }

}