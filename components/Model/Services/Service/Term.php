<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Terms;
use Components\Model\TermRelationships;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;


class Term extends \Components\Model\Services\Service
{
	
   
      
    public function saveTermsInPosts($terms_array, $object)
    {   

        $termsId = [];
        $post_id    = $object->getId();

        $postTerms = TermRelationships::find(
            [
                'post_id = ?0',
                'bind' => [$post_id]
            ]
        );

        foreach ($postTerms as $value) {
            $termsId[] = $value->term_id;
        }

        //Deleted tags
        $rows2 = array_diff( $termsId , $terms_array  );
        $this->deletedTag($post_id, $rows2);

        $rows = array_diff( $terms_array, $termsId  );
        foreach ($rows as $term_id) {
            $postTerm = new TermRelationships();
            $postTerm->setTermId($term_id);
            $postTerm->setPostId($post_id);

            if (!$postTerm->save()) {
                return false;
            }
            //Update the total of posts related to a tags
            // if ($object->getOperationMade() == \Phalcon\Mvc\Model::OP_CREATE) {
            //     $tags    = Tags::findFirstById($tagId);
            //     $number  = $tags->getNumberposts();
            //     $tags->setNumberPosts($number + 1);
            //     $tags->save();
            // }
        }

        di()->get('viewCache')->delete("metabox" .  $this->taxonomy);

        return true;
    }
    

 
    public function deletedTag($postId, $tagIds)
    {
        foreach ($tagIds as $id) {
            $object = TermRelationships::find(
                [
                    'post_id = ?0 AND term_id =?1',
                    'bind' => [$postId, $id]
                ]
            );

            if ($object) {
                $object->delete();
            }
        }
    }

}
