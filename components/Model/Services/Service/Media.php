<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Posts;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;

use claviska\SimpleImage;
use Components\Library\Media\MediaFiles;


class Media extends \Components\Model\Services\Service
{
	 
    public function generate_thumbnails($filename , $extension)
    {   

        foreach (MediaFiles::THUMBNAILS as $key => $value) {
             
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
