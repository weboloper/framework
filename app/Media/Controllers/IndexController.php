<?php

namespace App\Media\Controllers;

// use FroalaEditor\FroalaEditor_Image;
use Components\Model\Posts;

class IndexController extends Controller
{
    public function upload()
    {   
      

        if ($this->request->hasFiles()) {

            $this->setJsonResponse();

            $media   = new Posts();
            $uploads =  $this->request->getUploadedFiles();
            $this->view->disable();
            $uploaded = true;

            // $this->setJsonResponse();

            foreach ($uploads as $fileObj) {
                
                // $location = $media->initFile($fileObj);

                if(!$status = $media->initFile($fileObj)){
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

    public function list()
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
