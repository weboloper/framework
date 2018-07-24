<?php

namespace App\Media\Controllers;

// use FroalaEditor\FroalaEditor_Image;
use Components\Model\Posts;
use Components\Model\Model;
use Components\Library\Media\MediaType;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


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

            // $this->response->setStatusCode(404);
            // $this->jsonMessages['messages'][] = [
            //     'type'    => 'warning',
            //     'content' => 'Object not found!'
            // ];
            // return $this->jsonMessages;
            // if (!$uploaded) {
            //     $error = implode("\n", $media->getError());
            //     $this->response->setStatusCode(406, $error);
            //     response()->setContent($error);
            // } else {
            //     $this->response->setStatusCode(200, t("Success"));
            // }
            // return $this->response->send();
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

        return view('browser')->with('attachmentType',  $attachmentType);
    }

    public function list()
    {   

        $perPage = 25;
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset     = ($page - 1) * $perPage + 1;

        $attachmentType   = request()->getQuery('type', 'string');
        

        list($itemBuilder, $totalBuilder) =
                Model::prepareQueriesPosts( false , "p.type = 'attachment'", $perPage);

        if($attachmentType =='image'){
            $type_keyConditions = 'p.mime_type = "image"';
            $itemBuilder->andWhere($type_keyConditions);
            $totalBuilder->andWhere($type_keyConditions);

        }elseif($attachmentType) {
            $type_keyConditions = 'p.mime_type != "image"';
            $itemBuilder->andWhere($type_keyConditions);
            $totalBuilder->andWhere($type_keyConditions);
        }
        

        $totalPosts = $totalBuilder->getQuery()->setUniqueRow(true)->execute();

        if ($page > 1) {
            $itemBuilder->offset($offset);
        }
        $objects = $itemBuilder->getQuery()->execute();

        $resource = new Collection($objects, function(Posts $post) {

            $thumb_sizes = Posts::POST_THUMBNAILS;
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

    public function listjs()
    {
        // die(var_dump(222));
        $this->view->disable();

        $output =  'var tinyMCEImageList = new Array(';
            // Name, URL
        $output .=    '["Logo 1", "logo.swf"],';
        $output .=      '["Logo 2 Over", "logo_over.fla"]';
        $output .=  ');';

        $this->response->setStatusCode(200,  "Success" );
        $this->response->setHeader("Content-Type","text/javascript");
        $this->response->setHeader("pragma","no-cache");
        $this->response->setHeader("expires","0'");
        $this->response->setContent( $output );
 
        return $this->response->send();
 
    }
}
