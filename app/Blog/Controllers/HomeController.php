<?php

namespace App\Blog\Controllers;

use Components\Model\Services\Service\Post as postService;

class HomeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        $this->middleware('auth', [
            'only' => [
                'logged',
            ],
        ]);

        $this->postService = new postService;

    }

    public function index()
    {
        // die(var_dump($this->config->path->views));
        // die(var_dump(resources_path("views/page/")));
        // return (file_exists(resources_path("views/page/page.volt")));
        
      
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
        
        return view('pages.page' )
                      ->with('page', $page);
    }


    /**
     * GET | This shows the final landing page, in which it is the newsfeed.
     *
     * @return mixed
     */
    public function welcome()
    {   
        $this->tag->prependTitle("Home - ");
        return view('welcome');
    }

    public function logged()
    {

        return view('logged');
    }
}