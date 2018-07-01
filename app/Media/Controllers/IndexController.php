<?php

namespace App\Media\Controllers;

// use FroalaEditor\FroalaEditor_Image;

class IndexController extends Controller
{
    public function upload()
    {   
        $base = config('path.root');

        // die(var_dump( realpath($base . '/.' ) ));


        require  realpath($base . '/.' ).'\vendor\froala\wysiwyg-editor-php-sdk\lib\froala_editor.php';

        # code...
        try {
          $response =  \FroalaEditor_Image::upload('/uploads/');
          echo stripslashes(json_encode($response));
        }
        catch (Exception $e) {
          http_response_code(404);
        }
    }
}
