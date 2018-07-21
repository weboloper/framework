<?php

namespace App\Main\Controllers;

use Components\Model\Services\Service\Post as postService;

class IndexController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {

        $this->postService = new postService;

    }

    public function welcome()
    {
        return view('pages.welcome' );
    }

    public function index()
    {
        $router = $this->dispatcher->getParam('router');
        if (empty($router)) {
            $router = 'page';
        }
        
        $page = $this->postService->getFirstBySlug($router);

        $file = url_trimmer(config()->path->views.'/pages/'. $router .'.volt');

        if ( file_exists($file))   {
            return view('pages.'. $router )
                      ->with('page', $page);
         }
        
        return view('pages.page' )->with('page', $page);
    }


   
    
}