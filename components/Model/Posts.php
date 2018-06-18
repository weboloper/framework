<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\TermRelationships;
use Components\Model\Terms;
use Components\Model\PostMeta;
use Components\Model\Users;

use Phalcon\Mvc\Model\Behavior\Blameable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

use Components\Model\Audit;

class Posts extends Model
{
    use Timestampable;
    use SoftDeletable;


    const STATUS_PRIVATE = 'private';
    const STATUS_PUBLISH = 'publish';
    const STATUS_PENDING = 'pending';
    const STATUS_FUTURE  = 'future';
    const STATUS_TRASH   = 'trash';
    const STATUS_DRAFT   = 'draft';
    const STATUS_AUTODRAFT   = 'auto-draft';

    const POST_STATUS = [

        self::STATUS_DRAFT      => self::STATUS_DRAFT,
        self::STATUS_PUBLISH    => self::STATUS_PUBLISH,
        self::STATUS_PENDING    => self::STATUS_PENDING,
        self::STATUS_FUTURE     => self::STATUS_FUTURE,
        self::STATUS_TRASH      => self::STATUS_TRASH,
        self::STATUS_PRIVATE    => self::STATUS_PRIVATE,
 

    ];

    const POST_METAS = [

        'test_key'      => "test_key",
        'test_key_2'    => "test_key_2",

    ];




    public function getSource()
    {
        return 'posts';
    }

    public function initialize()
    {  
        $this->keepSnapshots(true);
        // if (auth()->isAuthorizedVisitor()) {
           $this->addBehavior(
                new Blameable(
                    [
                        'auditClass'       => Audit::class,
                    ]
                )
            );
        // }

        $this->addBehavior(
            new SoftDelete(
                [
                    "field" => "status",
                    "value" =>  "trash",
                ]
            )
        );


        $this->hasManyToMany(
            'id',
            TermRelationships::class,
            'post_id',
            'term_id',
            Terms::class,
            'term_id',
            ['alias' => 'terms']
        );

        $this->hasMany('id', PostMeta::class, 'post_id', ['alias' => 'meta', 'reusable' => true]);
        $this->belongsTo('user_id', Users::class, 'id', ['alias' => 'user', 'reusable' => true]);
    }

    public function beforeValidationOnCreate()
    {
        $this->status      = self::STATUS_AUTODRAFT;

    }
    /**
     * Implement hook beforeCreate
     *
     * Create a posts-views logging the ipaddress where the post was created
     * This avoids that the same session counts as post view
     */
    public function beforeCreate()
    {

    }

    public function get_post_meta($meta_key, $single = true)
    {
        $meta =  $this->getMeta(
            [
                "meta_key = :meta_key:",
                "bind" => [
                    "meta_key" => $meta_key
                ]
            ]
        );

        if($meta->count() > 0 ) {
            if($single) {
                $meta = $meta->getFirst();
                return $meta->meta_value;
            }

            $array = [];
            foreach ($meta as   $value) {
                $array[] =  $value->meta_value;
            }
            return $array;

        }
        
        
        return null;
        
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    public function getExcerpt()
    {
        return $this->excerpt;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }


}