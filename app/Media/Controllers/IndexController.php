<?php

namespace App\Media\Controllers;

// use FroalaEditor\FroalaEditor_Image;
use Components\Model\Posts;
use Components\Model\Users;
use Components\Model\Terms;
use Components\Model\Model;
use Components\Library\Media\MediaType;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Components\Library\Media\MediaFiles;


class IndexController extends Controller
{
    public function upload()
    {   
      

        if ($this->request->hasFiles()) {

            $this->setJsonResponse();

            $title   = request()->getPost('title', 'string');
            $acceptonly   = request()->getQuery('accept', 'string');

            $media   = new Posts();
            $uploads =  $this->request->getUploadedFiles();
            $this->view->disable();
            $uploaded = true;

            // $this->setJsonResponse();

            foreach ($uploads as $fileObj) {
                
                // $location = $media->initFile($fileObj);

                if($acceptonly == 'image') {
                    $fileExt     = $fileObj->getRealType();
                    $imageType   = new MediaType();
                    if (!$imageType->imageCheck($fileExt)) {
                            $this->response->setStatusCode(406,  "Success" );
                            $this->jsonMessages['messages'][] = [
                                'type'    => 'warning',
                                'content' =>  "Can't upload because file type's not allowed : ". $fileExt 
                            ];
                            return $this->jsonMessages;


                    }

                }
                
 
                if(!$status = $media->initFile($fileObj, $title)){
                    $uploaded = false;
                }
                // $this->response->setContent($location);
                // echo json_encode(array('location' =>   $location ));

            }

            if (!$uploaded) {
                $error = implode("\n", $media->getError());
                $this->response->setStatusCode(406, "$error");

                $this->jsonMessages['messages'][] = [
                    'type'    => 'warning',
                    'content' =>  $error
                ];
                return $this->jsonMessages;

             } else {
        
                $this->response->setStatusCode(200,  "Success" );
                $this->response->setJsonContent( $status );
            }
            return $this->response->send();

        } else {
            $this->response->setStatusCode(406,  "Error" );
            $this->jsonMessages['messages'][] = [
                    'type'    => 'warning',
                    'content' =>  'There is no file'
                ];
            return $this->jsonMessages;
        }

    }

    public function browser()
    {   

        $this->assets->addJs("https://unpkg.com/react@16/umd/react.development.js");
        $this->assets->addJs("https://unpkg.com/react-dom@16/umd/react-dom.development.js");
        $this->assets->addJs("https://unpkg.com/babel-standalone@6.15.0/babel.min.js");
        $this->assets->addJs("https://unpkg.com/axios/dist/axios.min.js");
        $this->assets->addCss("resources/statics/plugins/font-awesome/css/fontawesome-all.min.css");
        $this->assets->addCss("resources/statics/plugins/bootstrap/dist/css/bootstrap.min.css");
        $this->assets->addInlineCss("

         .media-item .inner img {
                width:100%;
                height:100%;
            }

          .media-item .card-footer {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
         
          }

          .media-item.selected .card {
            box-shadow:0px 0px 25px 0px green;
          }

         .upload-btn-wrapper {
              position: relative;
              overflow: hidden;
              display: inline-block;
            }
         .upload-btn-wrapper input[type=file] {
              font-size: 100px;
              position: absolute;
              left: 0;
              top: 0;
              opacity: 0;
            }

        ");

        $attachmentType   = request()->getQuery('type', 'string');
        $userId   = request()->getQuery('userId', 'int');
        $taxonomy   = request()->getQuery('taxonomy', 'int');

        // kategor seçtir
        $terms_array = null;
        // $terms_array[] =  [ 'term_id'=> '45' , 'name' => "Demo Hastanesi"]; 
        
        $terms_array = Terms::find(['taxonomy = "tag"' , 'columns' => ['name', 'term_id']])->toArray() ;

        // die(var_dump($terms_array));

        return view('browser')
            ->with('attachmentType',  $attachmentType)
            ->with('userId',  $userId)
            ->with('taxOptions', json_encode( $terms_array ))
            ->with('taxonomy',  $taxonomy );
    }

    public function list()
    {   

        $perPage = 25;
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset     = ($page - 1) * $perPage + 1;

        $attachmentType   = request()->getQuery('type', 'string');
        $userId   = request()->getQuery('userId', 'int');
        $taxonomy   = request()->getQuery('taxonomy', 'int');

        $params = [];
        if($taxonomy)
        {   
            $join = [
                'type'  => 'join',
                'model' => 'TermRelationships',
                'on'    => 'tr.post_id = p.id',
                'alias' => 'tr'
            ];
            list($itemBuilder, $totalBuilder) =
                Model::prepareQueriesPosts($join, "p.type = 'attachment'", $perPage);
            $itemBuilder->groupBy(array('p.id'));

            $params['taxonomy']  = $taxonomy ;
            $taxonomyConditions = 'tr.term_id = :taxonomy:';
            $itemBuilder->andWhere($taxonomyConditions);
            $totalBuilder->andWhere($taxonomyConditions);
             
        }else {
            list($itemBuilder, $totalBuilder) =
                Model::prepareQueriesPosts( false , "p.type = 'attachment'", $perPage);
        }

  

        if($attachmentType =='image'){
            $type_keyConditions = 'p.mime_type = "image"';
            $itemBuilder->andWhere($type_keyConditions);
            $totalBuilder->andWhere($type_keyConditions);

        }elseif($attachmentType) {
            $type_keyConditions = 'p.mime_type != "image"';
            $itemBuilder->andWhere($type_keyConditions);
            $totalBuilder->andWhere($type_keyConditions);
        }
        



        $totalPosts = $totalBuilder->getQuery()->setUniqueRow(true)->execute($params);

        if ($page > 1) {
            $itemBuilder->offset($offset);
        }
        $objects = $itemBuilder->getQuery()->execute($params);

        $resource = new Collection($objects, function(Posts $post) {

            $thumb_sizes = MediaFiles::THUMBNAILS;
            // $url =  substr(strrchr($post->slug,'.') , 1);
            $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $post->guid);


            $thumbs = [];
            foreach ($thumb_sizes as $key => $value) {
                
               $thumbs[$key] = $url . "-".$value[0]."x".$value[1] . '.jpg';
            }
            return [
                'id'      => (int) $post->id,
                'title'   => $post->title,
                'user_id' =>  $post->user_id,
                'type'    =>  $post->type,
                'slug'    =>  $post->slug,
                'guid'    =>  $post->guid,
                'parent_id'    =>  $post->parent_id,
                'mime_type'    =>  $post->mime_type,
                'created_at'    =>  $post->created_at,
                'thumbnails' =>  $thumbs

            ];
        });
        $fractal = new Manager();
        $array = $fractal->createData($resource)->toArray();

        $this->setJsonResponse();
        $this->response->setStatusCode(200, "OK");
        return $this->response->setJsonContent(  $array );

    }





    //META
    public function add_meta()
        {   
            if (request()->isPost()  && request()->isAjax()) {
                
                $this->setJsonResponse();

                $objectId = request()->getPost('objectId', ['striptags', 'trim' , 'int'] );
                $objectType = request()->getPost('objectType', ['striptags', 'trim' , 'alphanum'] );
                $metaId = request()->getPost('metaId', ['striptags', 'trim' , 'int'] );
                $metaKey = request()->getPost('meta_key', ['striptags', 'trim' , 'alphanum'] );
                $metaValue  = request()->getPost('meta_value', ['striptags', 'trim' , 'string']  );
                $btn_clicked  = request()->getPost('btn_clicked', ['striptags', 'trim' , 'string']  );


                if($btn_clicked =='delete' && $metaKey &&  $objectId && $objectType   )
                {
                    if($oldmetas = $this->metaService->has_meta($objectId , $metaKey , $objectType  ))
                    {
                        foreach ( $oldmetas as $meta) {
                            $meta->delete();
                        }

                    }
                    $this->response->setStatusCode(200,  "Success" );
                    $this->response->setJsonContent('table-danger');
                    return $this->response->send();

                }
                if( !$metaKey || !$metaValue || !$objectId || !$objectType ){
                        $this->jsonMessages['messages'][] = [
                                'type'    => 'warning',
                                'content' => 'Meta key and value required'
                            ];
                        return $this->jsonMessages;
                }


                if($metaKey == 'thumbnail'){

                    if (filter_var( $metaValue , FILTER_VALIDATE_URL) === FALSE) {
                        $this->jsonMessages['messages'][] = [
                            'type'    => 'warning',
                            'content' => 'Image not valid'
                        ];
                        return $this->jsonMessages;
                    }

                    if(!$this->metaService->validImage($metaValue)){
                        $this->jsonMessages['messages'][] = [
                            'type'    => 'warning',
                            'content' => 'Image not valid'
                        ];
                        return $this->jsonMessages;
                    }
           
                }

                $object =  $this->metaService->findObject($objectId ,$objectType);
                

                if (!$object) {
                    $this->jsonMessages['messages'][] = [
                        'type'    => 'warning',
                        'content' => 'Entity not found'
                    ];
                    return $this->jsonMessages;
                }

                 
                try{

                    if($oldmetas = $this->metaService->has_meta($objectId , $metaKey , $objectType ))
                    {
                        foreach ( $oldmetas as $meta) {
                            $meta->delete();
                        }

                    }
                    $meta = $this->metaService->add_meta( $objectId , $objectType ,  $metaKey, $metaValue);
                    
                    $this->response->setStatusCode(200,  "Success" );
                    $this->response->setJsonContent( $meta );
                    return $this->response->send();

                }catch ( Exception $e) {
                    $this->response->setStatusCode(406 ,  $e );
                    $this->jsonMessages['messages'][] = [
                        'type'    => 'warning',
                        'content' =>  $e 
                    ];
                    return $this->jsonMessages;
                }
     
            }

        }




    /**
     * To delete a record
     *
     * @param $id The id to be deleted
     *
     * @return void
     */
    public function delete_thumbnail()
    {
        if (request()->isPost()  && request()->isAjax()) {


            $this->setJsonResponse();

            $objectId = request()->getPost('objectId', ['striptags', 'trim' , 'int'] );
            $objectType = request()->getPost('objectType', ['striptags', 'trim' , 'alphanum'] );

            $object =  $this->metaService->findObject($objectId ,$objectType);
                

            if (!$object) {
                $this->jsonMessages['messages'][] = [
                    'type'    => 'warning',
                    'content' => 'Entity not found'
                ];
                return $this->jsonMessages;
            }

            
            $this->metaService->delete_by_meta_key( $objectId , $objectType , 'thumbnail' );
            $this->response->setStatusCode(200,  "Success" );
            $this->response->setJsonContent( "done!" );
            return $this->response->send();

        }
       

    }






}
