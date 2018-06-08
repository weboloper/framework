<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\TermRelationships;
use Components\Model\Terms;
use Components\Model\PostMeta;

use Components\Model\Behavior\Blameable as ModelBlameable;

class Posts extends Model
{
    use Timestampable;
    use SoftDeletable;

    public function getSource()
    {
        return 'posts';
    }

    public function initialize()
    {   
        $this->addBehavior(new ModelBlameable());
        $this->keepSnapshots(true);
        $this->hasManyToMany(
            'id',
            TermRelationships::class,
            'post_id',
            'term_id',
            Terms::class,
            'term_id',
            ['alias' => 'terms']
        );

        $this->hasMany('id', PostMeta::class, 'meta_id', ['alias' => 'meta', 'reusable' => true]);
    }

    public function meta($meta_key)
    {
        $value =  $this->getMeta(
            [
                "meta_key = :meta_key:",
                "bind" => [
                    "meta_key" => $meta_key
                ]
            ]
        )->getFirst();

        if($value)
        {
            return $value->meta_value;
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