<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Posts;
use Components\Utils\Slug;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;

use claviska\SimpleImage;


class Post extends \Components\Model\Services\Service
{
	
      public function demo()
      {
        return "works";
      }
    /**
     * Finds User by ID.
     *
     * @param  int $id The User ID.
     * @return Users|null
     */
    public function findFirstById($id)
    {
        return Posts::findFirstById($id) ?: null;
    }
    /**
     * Get User by ID.
     *
     * @param  int $id The User ID.
     * @return Users
     *
     * @throws Exceptions\EntityNotFoundException
     */
    public function getFirstById($id)
    {
        if (!$post = $this->findFirstById($id)) {
            throw new EntityNotFoundException($id);
        }
        return $post;
    }

    /**
     * Finds User by ID.
     *
     * @param  int $id The User ID.
     * @return Users|null
     */
    public function findFirstBySlug($slug)
    { 
        $post = Posts::query()
            ->where('slug = :slug:', ['slug' => $slug])
            ->limit(1)
            ->execute();
        return $post->valid() ? $post->getFirst() : null; 
    }  

    public function findByUser_id($id)
    {
        $post = Posts::query()
            ->where('user_id = :id:', ['id' => $id])
            ->limit(1)
            ->execute();
        return $post->valid() ? $post->getFirst() : null;
    }

    public function getFirstBySlug($slug)
    {
        if (!$post = $this->findFirstBySlug($slug)) {
            throw new EntityNotFoundException($slug);
        }
        return $post;
    }



    public function checkSlug($slug, $type )
    {
        return Posts::findFirst([
            'type= :type: AND slug = :slug:',
            'bind' => [
                'type' => $type,
                'slug' => $slug
            ]
        ]);
    }

    public function getUniqueSlug($slug_root , $objectType, Posts $object = null )
    {   
        $slug = Slug::generate($slug_root);

        if($exists = $this->checkSlug($slug ,   $objectType  ))
        {      
            if($object) {
                if( $exists->getId() != $object->getId() ){
                    return $this->getUniqueSlug(  $slug."-2" ,  $objectType ,  $object );
                }
            }else {
                return $this->getUniqueSlug(   $slug."-2" ,  $objectType );
            }       
        }

        return $slug;

    }

 
}
