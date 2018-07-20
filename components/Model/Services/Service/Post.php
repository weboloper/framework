<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Posts;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;

use claviska\SimpleImage;


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
 
    public function generate_thumbnails($filename , $extension)
    {   

        foreach (Posts::POST_THUMBNAILS as $key => $value) {
             
             try {
                  // Create a new SimpleImage object
                  $image = new SimpleImage();

                   $item = explode(".". $extension, $filename);
                   $newfilename = $item[0] . "-".$value[0]."x".$value[1] . '.jpg';

                  // Magic! ✨
                  $image
                    ->fromFile( resources_path( 'uploads/'. $filename)  )                     // load image.jpg
                    ->autoOrient()                              // adjust orientation based on exif data
                    ->thumbnail( $value[0] , $value[1] , 'center')                          // resize to 320x200 pixels
                    ->toFile( resources_path(  'uploads/'. $newfilename ) , 'image/jpeg');      // convert to PNG and save a copy to new-image.png
                    // ->toScreen();                               // output to the screen

                  // And much more! 💪
                } catch(Exception $err) {
                  // Handle errors
                  echo $err->getMessage();
                }

        }

    }
}
