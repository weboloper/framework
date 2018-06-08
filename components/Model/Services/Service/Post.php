<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Posts;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;


class Post extends \Components\Model\Services\Service
{
	
      
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

    public function getFirstBySlug($slug)
    {
        if (!$post = $this->findFirstBySlug($slug)) {
            throw new EntityNotFoundException($slug);
        }
        return $post;
    }
 
}
