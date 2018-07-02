<?php

namespace App\Media\Controllers;

// use FroalaEditor\FroalaEditor_Image;
use Components\Model\Posts;

class IndexController extends Controller
{
    public function upload()
    {   
      

        if ($this->request->hasFiles()) {

 
            $media   = new Posts();
            $uploads =  $this->request->getUploadedFiles();
            $this->view->disable();
            $uploaded = true;

            // $this->setJsonResponse();

            foreach ($uploads as $fileObj) {
                
               

               $x = $media->initFile($fileObj);

                echo json_encode(array('location' =>   $x ));

            }
            // if (!$uploaded) {
            //     $error = implode("\n", $media->getError());
            //     $this->response->setStatusCode(406, $error);
            //     response()->setContent($error);
            // } else {
            //     $this->response->setStatusCode(200, t("Success"));
            // }
            // return $this->response->send();
        }


    }
}
