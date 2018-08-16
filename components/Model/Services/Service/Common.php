<?php

namespace Components\Model\Services\Service;

use DateTime;
use DateTimeZone;
use Components\Model\Posts;
use Components\Model\Terms;

use Components\Exceptions\EntityNotFoundException;
use Components\Exceptions\EntityException;


class Common extends \Components\Model\Services\Service
{
 
   public function createGetSetsForModel($array)
   {
   		// $array model:COLUMN_MAP

   		$out = '';
   		foreach ($array as $key ) {

            if(strpos($key,"_") !== false){
               list($d, $l) = explode('_', $key, 2);

               $classname = $d.ucfirst($l);
            }else {
               $classname = $key;
            }
   			# code...
   			$getName = 'get'.ucfirst($classname);
   			$setName = 'set'.ucfirst($classname);

   			$out .= '</br>';
   			$out .= 'public function '. $getName.'()</br>';
   			$out .= '{</br>';
   			$out .= '&nbsp return $this->'.$key.';</br>';
   			$out .= '}</br>';

            $out .= '</br>';
            $out .= 'public function '. $setName.'($'. $key .')</br>';
            $out .= '{</br>';
            $out .= '&nbsp $this->'.$key.' = $'. $key.';</br>';
            $out .= '&nbsp return $this ;</br>';
            $out .= '}</br>';
   			 
   		}


         return $out;
   }

}
