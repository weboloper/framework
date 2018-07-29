<?php

namespace Components\Model\Services\Service;

use Components\Model\PostMeta;
use Components\Model\TermMeta;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;

use Exception;

class Meta extends \Components\Model\Services\Service
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
     * To has a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function has_meta($objectId, $meta_key)
    {   
        $meta = PostMeta::find(
            [
                'meta_key = :meta_key: AND  post_id = :post_id: ',
                'bind'       => [
                    'meta_key' => $meta_key,
                    'post_id' => $objectId
                ]
            ]
        );

        return $meta->valid() ? $meta : false; 
    }

     /**
     * To delete a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function delete_meta($metaId)
    {
        $meta = PostMeta::findFirstByMeta_id($metaId);

        if( ! $meta ) {
            throw new Exception(
                    "Object not found!" 
                );
        }

        if( $meta->delete() == false) {
            return false;
        }
        return true;


    }

    /**
     * To add a record
     *
     * @param $id The id to be add
     *
     * @return void
     */
    public function add_meta($objectId , $objectType,  $metaKey, $metaValue)
    {   
 
        if( $objectType == 'taxonomy' ){
            $meta = new TermMeta();
            $meta->setTermId($objectId); 
        }else {
            $meta = new PostMeta();
            $meta->setPostId($objectId); 
        }
        
        $meta->setMetaKey($metaKey);
        $meta->setMetaValue($metaValue);


        
        if (!$meta->save()) {

            
            foreach ($meta->getMessages() as $m) {
                throw new Exception(
                    "There is an error: ".$m->getMessage() 
                );
             }
        }

        return $meta; 

    }


    /**
     * To add a record
     *
     * @param $id The id to be add
     *
     * @return void
     */
    public function update_meta($metaId ,   $metaValue)
    {   
        $meta = PostMeta::findFirstByMeta_id($metaId);

        if( !$meta ) {
            throw new Exception(
                    "Object not found!" 
                );
        }
        $meta->setMetaValue($metaValue);
        
        if (!$meta->save()) {
            
            foreach ($meta->getMessages() as $m) {
                throw new Exception(
                    "There is an error: ".$m->getMessage() 
                );
             }
        }

        return $meta; 

    }

    public function validImage($url) {
       $size = getimagesize($url);
       return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);  
    }

}
