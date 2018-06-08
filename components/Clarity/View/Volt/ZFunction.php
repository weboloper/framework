<?php

namespace Components\Clarity\View\Volt;

use Phalcon\Di\Injectable;

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

}