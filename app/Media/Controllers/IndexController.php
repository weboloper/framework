<?php

namespace App\Media\Controllers;

// use FroalaEditor\FroalaEditor_Image;
use Components\Model\Posts;
use Components\Model\Model;


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

            $media   = new Posts();
            $uploads =  $this->request->getUploadedFiles();
            $this->view->disable();
            $uploaded = true;

            // $this->setJsonResponse();

            foreach ($uploads as $fileObj) {
                
                // $location = $media->initFile($fileObj);

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
        $this->assets->addCss("resources/statics/font-awesome/css/fontawesome-all.min.css");
        $this->assets->addCss("https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css");
        $this->assets->addInlineCss("
          .media-item {
            margin: .25rem;
          }
          .media-item .inner {
            width:100px;
            height:100px;
            background: #dcdcdc;
            text-align:center;
            font-weight:bold;
            line-height:100px;
            border:1px solid #bbb;
           }

          .media-item .footer {
            width:100px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            font-size:.8rem;
            padding: .125rem;
            border:1px solid #bbb;
            background: #ddd;
          }

          .media-item.selected {
            box-shadow:0px 0px 5px 0px green;
          }


        ");
        return view('browser');
    }

    public function list()
    {   
        

        $perPage = 25;
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset     = ($page - 1) * $perPage + 1;
        
     
        list($itemBuilder, $totalBuilder) =
                Model::prepareQueriesPosts( false , "p.type = 'attachment'", 5);

        $totalPosts = $totalBuilder->getQuery()->setUniqueRow(true)->execute();

        if ($page > 1) {
            $itemBuilder->offset($offset);
        }
        $objects = $itemBuilder->getQuery()->execute();

        $resource = new Collection($objects, function(Posts $post) {

            $thumb_sizes = Posts::POST_THUMBNAILS;
            // $url =  substr(strrchr($post->slug,'.') , 1);
            $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $post->slug);


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
