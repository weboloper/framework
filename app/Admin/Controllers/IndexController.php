<?php

namespace App\Admin\Controllers;

class IndexController extends Controller
{
     

    public function home()
    {
        return view('home');
    }

    public function filemanager()
    {
        return view('media');
    }

    public function createGetSetsForModel($model)
    {	
    	$modelName = '\Components\Model\\'. ucfirst($model);
    	
    	if(class_exists( $modelName )){
    		 $content = $this->commonService->createGetSetsForModel( $modelName::COLUMN_MAP);
       		 return view('page')->withContent( $content );
    	}
       
    }


}
