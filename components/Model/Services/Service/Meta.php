<?php

namespace Components\Model\Services\Service;

use Components\Model\PostMeta;
use Components\Model\TermMeta;
use Components\Model\UserMeta;

use Components\Model\Posts;
use Components\Model\Users;
use Components\Model\Terms;

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
    public function has_meta($objectId, $meta_key,  $objectType = 'post')
    {   
        switch ($objectType) {
            case 'term':
                # code...
                $className = new TermMeta;
                $key = 'term_id';
                break;
            case 'user':
                # code...
                $className = new UserMeta;
                $key = 'user_id';
                break;
            default:
                # code...
                $className =  new PostMeta;
                $key = 'post_id';
                break;
        }

        $meta =  $className::find(
            [
                'meta_key = :meta_key: AND  '. $key . ' = :objectId: ',
                'bind'       => [
                    'meta_key' => $meta_key,
                    'objectId' => $objectId
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
    public function delete_meta($metaId ,  $objectType = 'post' )
    {   
        switch ($objectType) {
            case 'term':
                # code...
                $className = new TermMeta;
                break;
            case 'user':
                # code...
                $className = new UserMeta;
                break;
            default:
                # code...
                $className =  new PostMeta;
                break;
        }

        $meta = "Components\Model\\" . $className::findFirstByMeta_id($metaId);

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
 
        if( $objectType == 'term' ){
            $meta = new TermMeta();
            $meta->setTermId($objectId); 
        }elseif( $objectType == 'user' ){
            $meta = new UserMeta();
            $meta->setUserId($objectId); 
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

    public function delete_by_meta_key($objectId, $objectType , $meta_key   ) {
        if($meta = $this->has_meta($objectId, $meta_key , $objectType ))
        {
            $meta->delete();
        }
    }


    public function findObject($objectId, $objectType ) {

        switch ($objectType) {
                case 'term':
                    # code...
                    return Terms::findFirstByTermId($objectId);
                    break;
                case 'user':
                    # code...
                    return Users::findFirstById($objectId);
                    break;
                case 'post':
                    # code...
                    return Posts::findFirstById($objectId);
                    break;
                default:
                    return null;
                    break;
            }
    }



    public function validImage($url) {
       $size = getimagesize($url);
       return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);  
    }

}
