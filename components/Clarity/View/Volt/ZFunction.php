<?php

namespace Components\Clarity\View\Volt;

use Phalcon\Di\Injectable;

use Components\Model\Posts;
use Components\Model\Users;

use Components\Library\Media\MediaFiles;

class ZFunction extends Injectable
{

	public static function timeAgo($date)
    {	
        $date = strtotime($date);
        $diff = time() - $date;
        if ($diff > (86400 * 30)) {
            return date('M j/y \a\t h:i', $date);
        } else {
            if ($diff > 86400) {
                return ((int)($diff / 86400)) . 'd ago';
            } else {
                if ($diff > 3600) {
                    return ((int)($diff / 3600)) . 'h ago';
                } else {
                    return ((int)($diff / 60)) . 'm ago';
                }
            }
        }
    }

 
    public static function getImageSrc($name = 'logo.png')
    {
     
        return "/resources/statics/img/$name";
    }

    public static function timeFormat($format, $date)
    {   
        $date = strtotime($date);
        return date( $format , $date);
    }

    public static function gravatar($email, $s = 32, $d = 'identicon', $r = 'pg')
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s={$s}&d={$d}&r={$r}";
        return $url;
    }


    public static function get_the_post_thumbnail( Posts $object ,  $size = 'small',   $attr = '' )
    {  

        $url = $object->get_meta('thumbnail');

        if (filter_var($url , FILTER_VALIDATE_URL) === FALSE) {
            return "/resources/statics/img/blank.png";
        }

        $thumb_sizes = MediaFiles::THUMBNAILS;
        // $url =  substr(strrchr($post->slug,'.') , 1);
        $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);

        return $url . "-".$thumb_sizes[$size][0]."x".$thumb_sizes[$size][1] . '.jpg';

    }
    public static function get_the_user_avatar( Users $object ,  $size = 'small',   $attr = '' )
    {  

        $url = $object->get_meta('thumbnail');

        if (filter_var($url , FILTER_VALIDATE_URL) === FALSE) {
            return "/resources/statics/img/avatar.png";
        }

        $thumb_sizes = MediaFiles::THUMBNAILS;
        // $url =  substr(strrchr($post->slug,'.') , 1);
        $url = preg_replace('/\\.[^.\\s]{3,4}$/', '', $url);

        return $url . "-".$thumb_sizes[$size][0]."x".$thumb_sizes[$size][1] . '.jpg';

    } 
    

    public static function get_file_icon( Posts $object   )
    {
        if($object->mime_type == 'image'){
            return '<i class="h1 far fa-file-image"></i>';
        }else if ($object->mime_type == 'video/mp4') {
            return '<i class="h1 far fa-file-video"></i>';
        }else if ($object->mime_type == 'application/pdf') {
            return '<i class="h1 far fa-file-pdf"></i>';
        }else if ($object->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            return '<i class="h1 far fa-file-word"></i>';
        }else {
            return '<i class="h1 far fa-file-powerpoint"></i>';
        }
        
    }

    public static function get_file_preview( Posts $object   )
    {
        if($object->mime_type == 'image'){
            return '<img src="'.$object->getSlug().'" width="50px">';
        }else if ($object->mime_type == 'video/mp4') {
            return '<i class="h1 far fa-file-video"></i>';
        }else if ($object->mime_type == 'application/pdf') {
            return '<i class="h1 far fa-file-pdf"></i>';
        }else if ($object->mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            return '<i class="h1 far fa-file-word"></i>';
        }else {
            return '<i class="h1 far fa-file-powerpoint"></i>';
        }
        
    }

}