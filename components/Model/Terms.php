<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\TermRelationships;
use Components\Model\TermMeta;
use Components\Model\PostMeta;
use Components\Model\Posts;

class Terms extends Model
{   
     // di()->get('viewCache')->delete("metaboxlocation");

    use Timestampable;
    use SoftDeletable;

  

    // const TYPE_CATEGORY = [
    //     'name' => 'Categories',
    //     'taxonomy' => 'category',
    //     // 'posts' => [ 'post'  ],
    //     'metas' => [ ],
    //     'icon' => "folder",
    //     'multiple' => true,
    //     'hierachical' => true,
    //     'tagging'  => false
    // ];

    const TYPE_TAG = [
        'name' => 'Tags',
        'taxonomy'  => 'tag',
        // 'posts' => [ 'post'  ],
        'metas' => [ 'test_key_1'  => 'test_key_1' ],
        'icon' => "tag",
        'multiple' => true,
        'hierachical' => false,
        'tagging'  => true
    ];

 

    // const TYPE_FORMAT = [
    //     'name' => 'Formats',
    //     'taxonomy' => 'format',
    //     // 'posts' => [ 'post'  ],
    //     'metas' => [ ],
    //     'icon' => "bookmark",
    //     'multiple' => false,
    //     'hierachical' => true,
    //     'tagging'  => false
    // ];

    const TYPE_LOCATION = [
        'name' => 'Cities',
        'taxonomy' => 'location',
        // 'posts' => [ 'post'  ],
        'metas' => [ ],
        'icon' => "folder",
        'multiple' => true,
        'hierachical' => true,
        'tagging'  => false
    ];

    #register post types
    const TERM_TYPES = [
        // self::TYPE_CATEGORY['taxonomy'] =>  self::TYPE_CATEGORY,
        self::TYPE_TAG['taxonomy'] =>  self::TYPE_TAG,
        // self::TYPE_FORMAT['taxonomy'] =>  self::TYPE_FORMAT,
        self::TYPE_LOCATION['taxonomy'] =>  self::TYPE_LOCATION,
     ];



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


        $this->hasMany('term_id', Terms::class, 'parent_id', ['alias' => 'children' ]);
        $this->belongsTo('parent_id', Terms::class, 'term_id', ['alias' => 'parent', 'reusable' => true]);

    }

    public function getName(){
        return $this->name;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
    
    public function afterSave()
    {
        // Convert the string to an array
        di()->get('viewCache')->delete("metabox" .  $this->taxonomy);
    }

}